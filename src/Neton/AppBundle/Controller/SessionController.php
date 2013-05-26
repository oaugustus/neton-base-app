<?php

namespace Neton\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Controlador padrão do framework que integra e disponibiliza funções para 
 * o gerenciamento, acesso e manipulação da sessão do usuário no sistema.
 * 
 * @author Otávio Fernandes <otavio@netonsolucoes.com.br>
 */
class SessionController extends Controller
{
    /**
     * Recupera a sessão registrada para o usuário no sistema.
     * 
     * @return array/boolean
     */
    protected function getSession()
    {
        // recupera o serviço de manipulação da sessão
        $sessionService = $this->get('session');

        // retorna a sessão do usuário ou falso
        if ($sessionService->get('session')){
            return $sessionService->get('session'); 
        }
        
        return false;
    }
    
    /**
     * Verifica se existe uma sessão aberta para o usuário no sistema.
     * 
     * @return boolean
     */
    protected function hasSession()
    {
        // se existe uma sessão aberta para o usuário
        if ($this->getSession()){
            
            // retorna true
            return true;
        }
        
        // caso contrário, retorna falso
        return false;
    }
    
    /**
     * Registra (abre) uma sessão para um  usuário.
     * 
     * @param array $data Dados da sessão do usuário
     */
    protected function registerSession($data)
    {
        // recupera o serviço de manipulação da sessão
        $sessionService = $this->get('session');
        
        // seta os dados da nova sessão
        $sessionService->set('session',$data);
    }
    
    /**
     * Atualiza uma sessão (uma chave da sessão) ou várias chaves dessa sessão.
     * 
     * @param Mixed $key Array composto de chaves valores ou chave string
     * @param Mixed $value valor da chave, caso seja uma string
     */
    protected function updateSession($keys, $value = null)
    {
        // tenta localizar a sessão aberta para o usuário
        $session = $this->getSession();
        
        // se a sessão existir
        if ($session){
            
            // equaliza os parâmetros do método
            if (!is_array($keys)){
                $keys = array(
                    $keys => $value
                );
            }

            // atualiza os elementos da sessão
            foreach ($keys as $key => $value){
                $session[$key] = $value;
            }
            
            // regrista os novos dados da sessão
            $this->registerSession($session);
            
        }
    }
    
    /**
     * Destroí a sessão aberta para o usuário.
     */
    protected function destroySession()
    {
        // destrói a sessão aberta para o usuário        
        $this->get('session')->remove('session');
    }
    
    /**
     * Recupera uma chave da sessão registrada para o usuário.
     * 
     * @param string $key 
     * @return Mixed
     */
    protected function getSessionVar($key)
    {
        $session = $this->getSession();
        
        if ($session) {
            if (isset($session[$key])){
                return $session[$key];
            } else {
                throw new \Exception(sprint_f('A variável de sessão "%s" não existe!',$key));
            }
        }
    }
    	
    /**
     * Create an ExtJS valid result.
     * @param  array $result
     * @return array
     */
    protected function createStoreResult(array $result, $totals = null)
    {

        return array(
            'total' => ($totals != null) ? $totals : count($result),
            'results' => $result
        );
    }   
        
    
}