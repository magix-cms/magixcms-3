<?php
class install_controller_employee extends install_db_employee{

    public $lastname_admin,
        $firstname_admin,
        $pseudo_admin,
        $email_admin,
        $passwd_admin,
        $title_admin;
    public function __construct()
    {
        $formClean = new form_inputEscape();
        if (http_request::isPost('lastname_admin')) {
            $this->lastname_admin = $formClean->simpleClean($_POST['lastname_admin']);
        }
        if (http_request::isPost('firstname_admin')) {
            $this->firstname_admin = $formClean->simpleClean($_POST['firstname_admin']);
        }
        if (http_request::isPost('pseudo_admin')) {
            $this->pseudo_admin = $formClean->simpleClean($_POST['pseudo_admin']);
        }
        if (http_request::isPost('email_admin')) {
            $this->email_admin = $formClean->simpleClean($_POST['email_admin']);
        }
        if (http_request::isPost('passwd_admin')) {
            $this->passwd_admin = $formClean->simpleClean(password_hash($_POST['passwd_admin'], PASSWORD_DEFAULT));
        }
        if (http_request::isPost('title_admin')) {
            $this->title_admin = $formClean->simpleClean($_POST['title_admin']);
        }
    }
    /**
     * Insertion de données
     */
    private function add(){
        parent::insert(
            array(
                'context'   =>    'employee',
                'type'      =>    'newEmployee'
            ),
            array(
                'keyuniqid_admin'   => filter_rsa::randUI(),
                'title_admin'       => $this->title_admin,
                'lastname_admin'    => $this->lastname_admin,
                'firstname_admin'   => $this->firstname_admin,
                'email_admin'       => $this->email_admin,
                'passwd_admin'      => $this->passwd_admin,
                'active_admin'      => 1
            )
        );
        $lastInsert = parent::fetchData(array('type' => 'lastEmployee'));
        parent::insert(
            array(
                'context' 	=> 'employee',
                'type'		=> 'employeeRel'
            ),
            array(
                'id_admin' 	=> $lastInsert['id_admin'],
                'id_role'	=> 1
            )
        );
    }
    /**
     *
     */
    public function run(){
        install_model_smarty::getInstance()->display('employee/index.tpl');
    }
}
?>