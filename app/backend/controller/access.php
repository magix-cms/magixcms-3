<?php
class backend_controller_access extends backend_db_access{

    public $edit, $action, $tabs;
    protected $message, $template, $header, $data;
    public $id,$id_access;

    public $id_role,$role_name,$id_module,$view,$append,$edit_access,$del,$action_access;
    public function __construct()
    {
        $this->template = new backend_model_template();
        $this->message = new component_core_message($this->template);
        $this->header = new http_header();
        $this->data = new backend_model_data($this);
        $formClean = new form_inputEscape();

        // --- GET
        if (http_request::isGet('edit')) {
            $this->edit = $formClean->numeric($_GET['edit']);
        }
        if (http_request::isGet('action')) {
            $this->action = $formClean->simpleClean($_GET['action']);
        } elseif (http_request::isPost('action')) {
            $this->action = $formClean->simpleClean($_POST['action']);
        }
        if (http_request::isGet('tabs')) {
            $this->tabs = $formClean->simpleClean($_GET['tabs']);
        }

        // --- POST

        if (http_request::isPost('id')) {
            $this->id = $formClean->numeric($_POST['id']);
        }
        if (http_request::isPost('id_access')) {
            $this->id_access = $formClean->numeric($_POST['id_access']);
        }
        if (http_request::isPost('role_name')) {
            $this->role_name = $formClean->simpleClean($_POST['role_name']);
        }
        if (http_request::isPost('id_role')) {
            $this->id_role = $formClean->numeric($_POST['id_role']);
        }
        if (http_request::isPost('id_module')) {
            $this->id_module = $formClean->numeric($_POST['id_module']);
        }
        if (http_request::isPost('view')) {
            $this->view = $formClean->numeric($_POST['view']);
        }
        if (http_request::isPost('append')) {
            $this->append = $formClean->numeric($_POST['append']);
        }
        if (http_request::isPost('edit')) {
            $this->edit_access = $formClean->numeric($_POST['edit']);
        }
        if (http_request::isPost('del')) {
            $this->del = $formClean->numeric($_POST['del']);
        }
        if (http_request::isPost('action')) {
            $this->action_access = $formClean->numeric($_POST['action']);
        }

    }

    /**
     * Assign data to the defined variable or return the data
     * @param string $context
     * @param string $type
     * @param string|int|null $id
     * @return mixed
     */
    private function getItems($type, $id = null, $context = null) {
        return $this->data->getItems($type, $id, $context);
    }

    /**
     * Insertion de données
     * @param $data
     */
    private function add($data)
    {
        switch ($data['type']) {
            case 'newRole':
                parent::insert(
                    array(
                        'context'   =>    'role',
                        'type'      =>    $data['type']
                    ),
                    array(
                        'role_name' => $this->role_name
                    )
                );
                $this->template->configLoad();
                $this->template->assign('data',$this->getItems('lastRole',null,'last'));
                $display = $this->template->fetch('access/loop/rows.tpl');
                $this->header->set_json_headers();
                $this->message->json_post_response(true,'add',$display);
                break;
            case 'newAccess':
                isset($this->view) ? $view = $this->view : $view = '0';
                isset($this->append) ? $append = $this->append : $append = '0';
                isset($this->edit_access) ? $edit_access = $this->edit_access : $edit_access = '0';
                isset($this->del) ? $del = $this->del : $del = '0';
                isset($this->action_access) ? $action_access = $this->action_access : $action_access = '0';

                parent::insert(
                    array(
                        'context'   =>    'access',
                        'type'      =>    $data['type']
                    ),
                    array(
                        'id_role'      => $this->id,
                        'id_module'    => $this->id_module,
                        'view'  => $view,
                        'append'   => $append,
                        'edit_access'  => $edit_access,
                        'del'=> $del,
                        'action_access'=> $action_access
                    )
                );
                $this->template->configLoad();
                $this->template->assign('row',$this->getItems('lastAccess', $this->id, 'last'));
                $display = $this->template->fetch('access/loop/perms.tpl');
                $this->header->set_json_headers();
                $this->message->json_post_response(true,'add',$display);
                break;
        }
    }
    /**
     * Insertion de données
     * @param $data
     */
    private function upd($data)
    {
        switch ($data['type']) {
            case 'role':
                parent::update(
                    array(
                        'context'   =>    'role',
                        'type'      =>    $data['type']
                    ),
                    array(
                        'id_role'   => $this->id,
                        'role_name'    => $this->role_name
                    )
                );
                $this->header->set_json_headers();
                $this->message->json_post_response(true,'update',$this->id);
                break;
            case 'access':

                isset($this->view) ? $view = $this->view : $view = '0';
                isset($this->append) ? $append = $this->append : $append = '0';
                isset($this->edit_access) ? $edit_access = $this->edit_access : $edit_access = '0';
                isset($this->del) ? $del = $this->del : $del = '0';
                isset($this->action_access) ? $action_access = $this->action_access : $action_access = '0';

                parent::update(
                    array(
                        'context'   =>    'access',
                        'type'      =>    $data['type']
                    ),
                    array(
                        'id_access'    => $this->id,
                        'view'  => $view,
                        'append'   => $append,
                        'edit_access'  => $edit_access,
                        'del'=> $del,
                        'action_access'=> $action_access
                    )
                );
                $this->header->set_json_headers();
                $this->message->json_post_response(true,'update',$this->id);
                break;
        }
    }
    /**
     * Insertion de données
     * @param $data
     */
    private function del($data){
        switch($data['type']){
            case 'delRole':
                parent::delete(
                    array(
                        'context'   =>    'role',
                        'type'      =>    $data['type']
                    ),
                    $data['data']
                );
                $this->header->set_json_headers();
                $this->message->json_post_response(true,'delete',$data['data']);
                break;
        }
    }
    /**
     * Execute run
     */
    public function run(){
        if(isset($this->action)) {
            switch ($this->action) {
                case 'add':
                    if (isset($this->role_name)) {
                        $this->add(
                            array(
                                'type'=>'newRole'
                            )
                        );
                    }else {
                        if (isset($this->tabs)) {
                            switch ($this->tabs) {
                                case 'perms':
                                    $this->add(
                                        array(
                                            'type' => 'newAccess'
                                        )
                                    );
                                    break;
                            }
                        }
                    }
                    break;
                case 'edit':
                    if (isset($this->tabs)) {
                        switch ($this->tabs) {
                            case 'perms':
                                $this->upd(
                                    array(
                                        'type' => 'access'
                                    )
                                );
                                break;
                        }
                    }else{
                        if (isset($this->role_name)) {
                            $this->upd(
                                array(
                                    'type' => 'role'
                                )
                            );
                        }else{
                            $this->getItems('lastAccess', $this->edit, 'last');
                            $this->getItems('module');
                            $this->getItems('role',$this->edit);
                            $this->getItems('access',$this->edit,'all');
                            $this->template->display('access/edit.tpl');
                        }
                    }

                    break;
                case 'delete':
                    if(isset($this->id)) {
                        if(isset($this->tabs)) {
                            switch ($this->tabs) {

                            }
                        } else {
                            $this->del(
                                array(
                                    'type'=>'delRole',
                                    'data'=>array(
                                        'id' => $this->id
                                    )
                                )
                            );
                        }
                    }
                    break;
            }
        }else{
            $this->getItems('roles');
            $this->template->display('access/index.tpl');
        }
    }
}
?>