<?php

class AdminProductFeatureInformationController extends ModuleAdminController{
    public function __construct()
    {
      $db = \Db::getInstance();
      $id_lang = Context::getContext()->language->id;

      $this->table = "product_information";
      $this->className = "ProductinformationClass";

      $this->fields_list = array(
        'id_product_information'=> array(
            'title' => Context::getContext()->getTranslator()->trans("ID", [], 'Modules.ProductFeatureInformation.Admin'),
            'align' => 'center',
            'class' => 'fixed-width-xs'
          ),
        'image' => array(
            'title' => Context::getContext()->getTranslator()->trans('Logo', [], 'Modules.ProductFeatureInformation.Admin'),
            'orderby' => false,
            'callback' => 'displayLogo',
            'callback_object' => $this,
            'filter' => false,
            'search' => false,
            'class' => 'fixed-width-xl'
        ),
        'name' => array(
            'title' => Context::getContext()->getTranslator()->trans('Feature Name', [], 'Modules.ProductFeatureInformation.Admin'),
            'orderby' => true,
            'class' => 'fixed-width-xxl'
          ),
        'content' => array(
            'title' => Context::getContext()->getTranslator()->trans('Content', [], 'Modules.ProductFeatureInformation.Admin'),
            'orderby' => true,
            'class' => 'fixed-width-xxl'
          ),
        'value' => array(
            'title' => Context::getContext()->getTranslator()->trans('Value', [], 'Modules.ProductFeatureInformation.Admin'),
            'orderby' => true,
            'class' => 'fixed-width-xxl'
          ),
          'active' => array(
              'title' => Context::getContext()->getTranslator()->trans('Status', [], 'Modules.ProductFeatureInformation.Admin'),
              'align' => 'center',
        			'active' => 'status',
        			'type' => 'bool',
        			'width' => 25,
              'search' => false,
              'orderby' => false,
            ),
      );

      $this->actions = ['edit', 'delete'];

      $this->bulk_actions = array(
            'delete' => array(
                'text'    => 'Delete selected',
                'icon'    => 'icon-trash',
                'confirm' => 'Delete selected items?',
            ),
        );

        $query = '
          SELECT ' . _DB_PREFIX_ . 'feature.id_feature, name FROM ' . _DB_PREFIX_ .
          'feature INNER JOIN ' . _DB_PREFIX_ . 'feature_lang ON (' . _DB_PREFIX_ . 'feature.id_feature = '
          . _DB_PREFIX_ . 'feature_lang.id_feature AND ' . _DB_PREFIX_ . 'feature_lang.id_lang = '.$id_lang.')';
        $selected = $db::getInstance()->ExecuteS($query);

        $max = count($selected);
        $options = [];
        for ($i = 0; $i < $max; $i++) {
          $options[$i] = [
            'id_option' => $selected[$i]['name'],
            'name' => $selected[$i]['name'],
          ];
        }
        $this->fields_form = [
            'legend' => [
                'title' => Context::getContext()->getTranslator()->trans('Settings', [], 'Modules.ProductFeatureInformation.Admin'),
            ],
            'input' => [
                [
                    'type' => 'select',
                    'label' => Context::getContext()->getTranslator()->trans('Feature Name', [], 'Modules.ProductFeatureInformation.Admin'),
                    'name' => 'name',
                    'size' => 1,
                    'required' => true,
                    'options' => array(
                    'query' => $options,
                    'id' => 'id_option',
                    'name' => 'name'
                  ),

                ],
                [
                    'type' => 'text',
                    'label' => Context::getContext()->getTranslator()->trans('Content', [], 'Modules.ProductFeatureInformation.Admin'),
                    'name' => 'content',
                    'required' => true
                ],
                [
                    'type' => 'select',
                    'label' => Context::getContext()->getTranslator()->trans('Value', [], 'Modules.ProductFeatureInformation.Admin'),
                    'name' => 'value',
                    'size' => 1,
                    'disabled' => true,
                    'required' => true,
                    'options' => array(
                      'query' => null,
                      'id' => 'id_option',
                      'name' => 'name'
                    ),
                ],
                [
                    'type' => 'file',
                    'label' => Context::getContext()->getTranslator()->trans('Picture', [], 'Modules.ProductFeatureInformation.Admin'),
                    'name' => 'image',
                    'size' => 20,
                    'required' => true
                ],
                [
                 'type' => 'switch',
                 'label' => Context::getContext()->getTranslator()->trans('Status', [], 'Modules.ProductFeatureInformation.Admin'),
                 'name' => 'active',
                 'is_bool' => true,
                           'default_value' => 1,
                 'values' => array(
                   array(
                     'id' => 'active_on',
                     'value' => 1,
                     'label' => Context::getContext()->getTranslator()->trans('Enabled', [], 'Modules.ProductFeatureInformation.Admin')
                   ),
                   array(
                     'id' => 'active_off',
                     'value' => 0,
                     'label' => Context::getContext()->getTranslator()->trans('Disabled', [], 'Modules.ProductFeatureInformation.Admin')
                   )
                           )
               ]
            ],
            'submit' => [
                'title' => Context::getContext()->getTranslator()->trans('Save', [], 'Modules.ProductFeatureInformation.Admin'),
                'class' => 'btn btn-default pull-right'
            ]
        ];

        if (isset($_FILES['image']))
  			{
    				$target_dir = _PS_MODULE_DIR_. '/productfeatureinformation/upload/';
    				$target_file = $target_dir . basename($_FILES['image']["name"]);
            $file_name = basename($_FILES['image']["name"]);
    				$uploadOk = 1;
    				$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    				// Check if image file is a actual image or fake image
    				if(isset($_POST["submit"]))
    				{
    					$check = getimagesize($_FILES['image']["tmp_name"]);
    					if($check !== false) {
    						$uploadOk = 1;
    					} else {
    						$uploadOk = 0;
    					}
    				}

    				// Allow certain file formats
    				if($imageFileType != "jpg" && $imageFileType != "JPG" && $imageFileType != "png" && $imageFileType != "PNG" 
            && $imageFileType != "JPEG" && $imageFileType != "jpeg" && $imageFileType != "GIF"
    				&& $imageFileType != "gif" ) {
    					$uploadOk = 0;
    				}
    				// Check if $uploadOk is set to 0 by an error
    				if ($uploadOk == 1)
    				{
    					if (move_uploaded_file($_FILES['image']["tmp_name"], $target_file))
    					{
    						echo "The file ". basename($_FILES['image']["name"]). " has been uploaded.";
    					}
            }
          }
      $this->bootstrap = true;
      parent::__construct();
    }

    public function displayLogo($path)
    {
        return '<img width="50px" src="'._MODULE_DIR_ .'productfeatureinformation/upload/'.$path.'">';
    }

    public function isEnabled($value) {
      if ($value) {
        return Context::getContext()->getTranslator()->trans("Yes", [], 'Modules.ProductFeatureInformation.Admin');
      } else {
        return Context::getContext()->getTranslator()->trans("No", [], 'Modules.ProductFeatureInformation.Admin');
      }
    }

  public function setMedia($isNewTheme = false) {
        $this->context->controller->addJS(__PS_BASE_URI__.'modules/productfeatureinformation/views/js/back.js');
        parent::setMedia();
    }
}
