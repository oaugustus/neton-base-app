<?php

namespace Neton\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Neton\DirectBundle\Annotation\Form;
use Neton\DirectBundle\Annotation\Remote;
use Symfony\Component\HttpFoundation\Response;


class SecurityController extends SessionController
{
    /**
     * Método de autentiacação do usuário.
     * 
     * @remote
     * @anonymous
     * @param {Array} $params
     */
    public function authAction($params)
    {
        /*
        // pega o gerenciador de entidades
        $em = $this->getDoctrine()->getManager();

         * Esqueleto do código de autenticação
        // tenta localizar o usuário pelo seu username e senha
        $found = $em->getRepository('NetonAppBundle:Usuario')->findToAuth($params['login'], $params['pass']);
        
        // se o usuário existir
        if ($found['message'] == 200) {
            $user = $found['user'];
            
            // registra a sessão para o usuário
            $this->registerSession(array(
                'user.id' => $user->getId(),
                'user.login' => $user->getLogin(),
                'user.name' => $user->getNome(),
                'user.pass' => md5($user->getSenha())
            ));

            // retorna a validação da antenticação
            $response = array(
                'code' => 200, // usuário localizado
                'secureUrl' => '../main/app.html'
            );
        } else {
            $response = array(
                'code' => $found['code'], // código de erro
            );
        }
        */

        // @mock
        $this->registerSession(array(
            'user.id' => '1',
            'user.login' => 'otavio',
            'user.name' => 'Otávio Fernandes',
            'user.pass' => md5('12345')
        ));

        $response = array(
            'success' => true,
            'message' => 'authenticated'
        );

        // @endmock

        // retorna o resultado da autenticação
        return $response;
    }

    /**
     * Troca a senha do usuário logado.
     *
     * @remote
     * @param $params
     */
    public function changePassAction($params)
    {
        /**
         * Esqueleto do método de alteração de senha
         *

        $em = $this->getDoctrine()->getManager();
        $pass = $this->getSessionVar('user.pass');
        $id = $this->getSessionVar('user.id');

        // verifica se a senha atual do usuário está correta
        if (md5($params['pass']) == $pass){

            $user = $em->getRepository('NetonAppBundle:Usuario')->find($id);
            $user->setSenha($params['newpass']);
            $this->updateSession('user.pass',md5($params['newpass']));

            $em->flush();

            return true;
        } else {
            return false;
        }
         */

        // @mock
        return true;
    }

    /**
     * Recupera a senha da conta do usuário.
     *
     * @remote
     * @anonymous
     * @param $params
     */
    public function recoverPassAction($params)
    {

        /**
         * Esqueleto do método de recuperação de senha

        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('NetonAppBundle:Usuario')->findOneBy(
            array(
                'email' => $params['email']
            )
        );

        if ($user){
            $message = \Swift_Message::newInstance()
                ->setSubject('Recuperação de senha')
                ->setFrom('suporte@contractor.com.br')
                ->setTo($params['email'])
                ->setBody(
                    'Sua senha é '.$user->getSenha()
                    /*$this->renderView(
                        'HelloBundle:Hello:email.txt.twig',
                        array('name' => $name)
                    )
                )
            ;
            $this->get('mailer')->send($message);

            return true;
        } else {
            return false;
        }
         */

        // @mock
        return true;
    }
    
    /**
     * Encerra uma sessão aberta para o usuário.
     * 
     * @remote
     * @anonymous
     * @param Array $params 
     */
    public function logoutAction($params)
    {
        // destrói a sessão aberta para o usuário
        $this->destroySession();
        
        // retorna o resultado da execução do método
        return true;
    }

    /**
     * Verifica se o usuário está logado
     * 
     * @remote
     * @anonymous
     * @param Array $params
     * @return [Boolean/Array]
     */
    public function isLoggedAction($params)
    {
        // tenta recuperar a sessão aberta para o usuário
        $session = $this->getSession();        
        
        // se a sessão não existir
        if (!$session){
            // define a sessão como inexistente
            $has = false;
        } else {
            // caso exista, retorna o array com os dados da sessão aberta
            $has = $session;
        }
        
        // retorna a falso ou a sessão aberta
        return $has;
    }
}