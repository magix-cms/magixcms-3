<?php
class backend_controller_revisions extends backend_db_revisions{
    /**
     * @var backend_model_template|object
     */
    protected $template;
    /**
     * @var backend_model_data
     */
    protected $data;
    /**
     * @var int
     */
    public int $id_lang;
    /**
     * @var int
     */
    public int $item_id;
    /**
     * @var string
     */
    public string $type;
    /**
     * @var string
     */
    public string $field;
    /**
     * @var string
     */
    public string $action;
    /**
     * @var int
     */
    public int $id;

    /**
     * backend_controller_pages constructor.
     * @param null|object $t
     */
    public function __construct($t = null)
    {
        $this->template = $t ? $t : new backend_model_template;
        $this->data = new backend_model_data($this);
    }
    /**
     * Assign data to the defined variable or return the data
     * @param string $type
     * @param string|int|null $id
     * @param string $context
     * @param boolean $assign
     * @param boolean $pagination
     * @return mixed
     */
    private function getItems($type, $id = null, $context = null, $assign = true, $pagination = false) {
        return $this->data->getItems($type, $id, $context, $assign, $pagination);
    }
    /**
     * Insert data
     * @param string $type
     * @param array $params
     */
    private function add(string $type, array $params) {
        switch ($type) {
            case 'addRevision':
                parent::insert($type, $params);
                break;
        }
    }

    /**
     * @param string $type
     * @param array $params
     * @return void
     */
    private function del(string $type, array $params) {
        switch ($type) {
            case 'delRevisions':
            case 'clearFullHistory':
                parent::delete($type, $params);
                break;
        }
    }
    /**
     * @param string $type
     * @param int $itemId
     * @param int $idLang
     * @param string $field
     * @return void
     */
    private function getList(string $type, int $itemId, int $idLang, string $field): void{
//        if ($itemId <= 0) {
//            echo json_encode([]);
//            exit;
//        }
        $historyList = $this->getItems('historyList',array('item_type'=>$type,'item_id'=>$itemId,'id_lang'=>$idLang,'field'=>$field),'all',false);
        $revisions = [];
        if (!empty($historyList)) {
            foreach($historyList as $history) {
                $revisions[] = [
                    'id'            => $history['id'],
                    // On s'assure que le nom correspond à ce que le JS attend
                    'date_register' => $history['date_register']
                ];
            }
        }
        header('Content-Type: application/json');
        echo json_encode($revisions);
        exit;
    }

    /**
     * @param int $id
     * @return void
     */
    private function getContent(int $id): void {
        // Appel de votre moteur Magix CMS pour récupérer une seule ligne
        $revision = $this->getItems('revisionContent', array('id' => $id), 'one', false);

        // On prépare la réponse JSON
        $response = [
            'content' => $revision ? $revision['content'] : ''
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    /**
     * @param string $type
     * @param int $itemId
     * @param int $idLang
     * @param string $field
     * @return void
     */
    private function purgeOldRevisions(string $type, int $itemId, int $idLang, string $field): void
    {
        // On utilise executeItem pour lancer la suppression
        $this->del('delRevisions', [
            'item_type'=> $type,
            'item_id' => $itemId,
            'id_lang' => $idLang,
            'field'   => $field
        ]);
    }

    /**
     * @param string $type
     * @param int $itemId
     * @param int $idLang
     * @param string $field
     * @return array|null
     */
    private function lastRevision(string $type, int $itemId, int $idLang, string $field): array
    {
        $last = $this->getItems('lastRevision', [
            'item_type' => $type,
            'item_id'   => $itemId, // Sera ici 1 si c'est un module mono-page
            'id_lang'   => $idLang,
            'field'     => $field
        ], 'one', false);

        return !empty($last) ? $last : [];
    }

    /**
     * @param string $type
     * @param int $itemId
     * @param int $idLang
     * @param string $field
     * @param $content
     * @return void
     */
    public function saveRevision(string $type, $itemId, int $idLang, string $field, $content): void
    {
        // 1. Normalisation de l'ID : si vide, null ou 0, on force à 1
        // On retire le typage strict 'int' du paramètre $itemId pour accepter les strings vides du JS
        $itemId = (!empty($itemId) && (int)$itemId > 0) ? (int)$itemId : 1;

        // 2. Sécurité : On ignore si le contenu est vide
        if (is_null($content) || empty(trim(strip_tags($content)))) {
            return;
        }

        // 3. On récupère le dernier snapshot
        $last = $this->lastRevision($type, $itemId, $idLang, $field);
        $lastContent = $last['content'] ?? '';

        // 4. On compare (trim pour éviter les espaces vides inutiles)
        if (trim($lastContent) !== trim($content)) {
            $this->add(
                'addRevision',
                [
                    'item_type' => $type,
                    'item_id'   => $itemId,
                    'id_lang'   => $idLang,
                    'field'     => $field,
                    'content'   => $content
                ]
            );

            // 5. Purge automatique (Limite à 10)
            $this->purgeOldRevisions($type, $itemId, $idLang, $field);
        }
    }
    private function clearFullHistory(string $type, int $itemId, int $idLang, string $field): void
    {
        $this->del('clearFullHistory', [
            'item_type' => $type,
            'item_id'   => $itemId,
            'id_lang'   => $idLang,
            'field'     => $field
        ]);
        echo json_encode(['success' => true]);
        exit;
    }

    /**
     * @return void
     */
    public function run(){
        $formClean = new form_inputEscape();
        //if(http_request::isMethod('GET')) {
            if(http_request::isGet('action')) $this->action = $formClean->simpleClean($_GET['action']);
            if(http_request::isGet('type')) $this->type = $formClean->simpleClean($_GET['type']);
            if(http_request::isGet('item_id')) $this->item_id = $formClean->numeric($_GET['item_id']);
            if(http_request::isGet('id_lang')) $this->id_lang = $formClean->numeric($_GET['id_lang']);
            if(http_request::isGet('field')) $this->field = $formClean->simpleClean($_GET['field']);
            if(http_request::isGet('id')) $this->id = $formClean->numeric($_GET['id']);
            if($this->action == 'get_list') {

                $this->getList($this->type, $this->item_id, $this->id_lang, $this->field);

            }elseif($this->action == 'get_content'){
                $this->getContent($this->id);
            }elseif($this->action == 'clear_history'){
                $this->clearFullHistory($this->type, $this->item_id, $this->id_lang, $this->field);
            }
        //}
        //$rev->saveRevision('pages', $id_page, $id_lang, 'content_pages', $html_content);
    }
}

?>