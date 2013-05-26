<?php

namespace Neton\AppBundle\Doctrine;

/**
 * Classe da aplicação do filtro de busca avançado.
 *
 */
class Filter
{
    /**
     * Construtor de consultas DQL.
     * @var \Doctrine\ORM\QueryBuilder
     */
    private $qb;
    
    /**
     * Constrói os filtros de acordo com o recebido da UI.
     * @var Array 
     */
    private $filters;

    /**
     * Aliases de mapeamento de entidades.
     * @var Array 
     */
    private $aliases;
    
    /**
     * Constrói o objeto de filtro.
     * 
     * @param \Doctrine\ORM\QueryBuilder $qb 
     * @param Array $filters
     */
    public function __construct($qb, $filters, $aliases)
    {
        $this->qb = $qb;       
        $this->aliases = $aliases;
        $this->filters = $filters;
    }
    
    /**
     * Aplica o filtro de busca.
     * 
     * @return \Doctrine\ORM\QueryBuilder 
     */
    public function apply()
    {
        foreach ($this->filters as $filter){
            // define o nome do método a ser chamado
            $method = 'create'.ucfirst($filter['type']).'Filter';
            
            // chama o método de criação do filtro
            $this->$method($filter);
        }
        
        return $this->qb;
    }
    
    /**
     * Cria um filtro do tipo string na consulta.
     * 
     * @param Array $filter 
     */
    private function createStringFilter($filter)
    {
        $field = $filter['field'];
        $searchField = $this->getSearchField($field);
        $value = "%".$filter['value']."%";
        
        $this->qb->andWhere($searchField.' LIKE :'.$field)
                 ->setParameter($field, $value);
        
    }
    
    /**
     * Cria um filtro do tipo boolean na consulta.
     * 
     * @param Array $filter 
     */
    private function createBooleanFilter($filter)
    {
        $field = $filter['field'];
        $searchField = $this->getSearchField($field);
        $value = $filter['value'] == '' ? '0' : '1';
        
        $this->qb->andWhere($searchField.' = :'.$field)
                 ->setParameter($field, $value);
        
    }
    
    
    /**
     * Recupera o nome do campo de busca.
     * 
     * @param String $field 
     * @return String
     */
    private function getSearchField($field)
    {
        $parts = explode('_', $field);
        
        if (count($parts) > 1){
            $field = $parts[0];
            $alias = $this->aliases[$parts[1]];
        } else {
            $field = $parts[0];
            $alias = $this->aliases[''];
        }
        
        return $alias.'.'.$field;
        
    }
}

