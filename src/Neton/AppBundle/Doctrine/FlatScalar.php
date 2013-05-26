<?php

namespace Neton\AppBundle\Doctrine;
use Doctrine\ORM\Internal\Hydration\ScalarHydrator;

/**
 * Flat scalar custom Hidration Mode
 *
 * @author Otávio Fernandes <otavio@neton.com.br>
 */
class FlatScalar extends ScalarHydrator
{
    /**
     * Flat an Scalar Hydrator.
     * @return array
     */
    protected function hydrateAllData()
    {
        $result = parent::hydrateAllData();
        $newResult = array();
                
        for ($i = 0; $i < count($result); $i++) {
            foreach ($result[$i] as $key => $value) {
                $parts = explode('_',$key);
                if (count($parts) > 1){
                    if (strlen($parts[0]) <= 2)
                        $key = $parts[1];
                    else {
                        $key = $parts[0]."_".$parts[1];
                    }
                }
                
                $newResult[$i][$key] = $value;
            }
        }

        return $newResult;
    }    
}