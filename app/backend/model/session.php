<?php
class backend_model_session {
    private $employee,$httpSession,$setting,$settings;

	/**
	 * backend_model_session constructor.
	 */
    public function __construct()
    {
        $this->employee = new backend_db_employee();
		$this->setting = new backend_model_setting();
		$this->settings = $this->setting->getSetting();
        $this->httpSession = new http_session($this->settings['ssl']);
    }

    /**
     * clean old register 2 days
     * @access private
     * @return void
     * @param $data
     */
    private function cleanOldSession($data) {
        //On supprime les enregistrements de plus de deux jours
        $date = new DateTime('NOW');
        $date->modify('-1 day');
        $limit = $date->format('Y-m-d H:i:s');
        $this->employee->delete(array('context'=>'session','type'=>'lastSession'),array('limit'=>$limit,'id_admin'=>$data['id_admin']));
    }

    /**
     * Open session
     * @param $userid
     * @return true
     */
    public function openSession($data){
        $this->employee->delete(array('context'=>'session','type'=>'currentSession'),array('id_admin'=>$data['id_admin']));
        $this->cleanOldSession(array('id_admin'=>$data['id_admin']));
        //On ajoute un nouvel identifiant de session dans la table
        $this->employee->insert(
            array(
                'context'=>'session',
                'type'=>'newSession'
            ),
            array(
                'id_admin_session'  => $data['id_admin_session'],
                'id_admin'          => $data['id_admin'],
				'expires' 			=> $data['expires'] ? $data['expires'] : null,
                'ip_session'        => $this->httpSession->getIp(),
                'browser_admin'     => $this->httpSession->getBrowser(),
                'keyuniqid_admin'   => $data['keyuniqid_admin']
            )
        );
        return true;
    }

    /**
     * @param bool $connexion
     */
    public function redirect($connexion=false){
        if($connexion){
            if (!headers_sent()) {
                header('location: '.http_url::getUrl().'/admin/index.php?controller=dashboard');
                exit;
            }
        }else{
            if (!headers_sent()) {
                header('location: '.http_url::getUrl().'/admin/index.php?controller=login');
                exit;
            }
        }
    }

    /**
     * close session
     * @return void
     */
    public function closeSession() {
        $this->employee->delete(array('context'=>'session','type'=>'sidSession'),array('id_admin_session'=>session_id()));
    }

    /**
     * Compare la session avec une entrée session mysql
     * @return void
     */
    public function compareSessionId(){
        return $this->employee->fetchData(
            array(
            	'context' => 'one',
                'type' => 'sid'
            )
        );
    }
}