<?php
require_once(_PS_MODULE_DIR_ . 'arkoninstagram/classes/InstagramDisplaySettings.php');

class Version
{
    const DESKTOP = 'desktop';
    const MOBILE = 'mobile';
}

class AdminArkonInstagramSettingsController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;

        $this->context = Context::getContext();
        parent::__construct();
    }

    public function postProcess()
    {
//        $this->handleRefresh();
        $this->processSettings();
        parent::postProcess();
    }

    public function processSettings()
    {
        if (Tools::isSubmit('submitAddconfiguration')) {
            $versions = [
                [
                    'id' => INSTAGRAM_DESKTOP_CONFIG_ID, 'version' => Version::DESKTOP
                ],
                [
                    'id' => INSTAGRAM_MOBILE_CONFIG_ID, 'version' => Version::MOBILE
                ],
            ];

            foreach($versions as $version){
                $settings = new InstagramDisplaySettings($version['id']);
                $prev_hook = $settings->hook;

                $settings->hook = Tools::getValue($version['version'] . '_display_hook');
                $settings->display_style = Tools::getValue($version['version'] . '_display_style');
                $settings->image_size = Tools::getValue($version['version'] . '_image_size');
                $settings->show_title = Tools::getValue($version['version'] . '_show_title');
                $settings->max_images_fetched = Tools::getValue($version['version'] . '_max_images_fetched');
                $settings->images_per_gallery = Tools::getValue($version['version'] . '_images_per_gallery');
                $settings->gap = Tools::getValue($version['version'] . '_gap');
                $settings->grid_row = Tools::getValue($version['version'] . '_grid_row');
                $settings->grid_column = Tools::getValue($version['version'] . '_grid_column');
                $settings->title = Tools::getValue($version['version'] . '_title');

                if (!Validate::isLoadedObject($settings)) {
                    if ($settings->add() && $prev_hook !== $settings->hook) {
                        $this->module->unregisterHook($prev_hook);
                        $this->module->registerHook($settings->hook);
                    }
                } else {
                    if ($settings->update() && $prev_hook !== $settings->hook) {
                        $this->module->unregisterHook($prev_hook);
                        $this->module->registerHook($settings->hook);
                    }
                }

                if (!$this->module->fetchImagesFromInstagram()) {
                    return;
                }

                $this->module->deleteLocalImages();
                $this->module->saveImagesLocally();
            }
        }
    }

    public function handleRefresh()
    {
        if (Tools::isSubmit('refresh')) {
            if (!$this->module->fetchImagesFromInstagram()) {
                return;
            }
            $this->module->deleteLocalImages();
            $this->module->saveImagesLocally();
        }
    }

//    public function renderList()
//    {
//        $settings = new InstagramDisplaySettings(INSTAGRAM_DESKTOP_CONFIG_ID);
//        $m_settings = new InstagramDisplaySettings(INSTAGRAM_MOBILE_CONFIG_ID);
//        $display_hooks = $this->getDisplayHooks();
//
//        $this->context->smarty->assign(array(
//            'is_connected' => $this->module->checkIfAccessTokenExists(),
//            'images_data' => $this->module->getImagesData(),
//            'settings' => $settings,
//            'm_settings' => $m_settings,
//            'display_hooks' => $display_hooks,
//        ));
////        return $this->context->smarty->fetch(_PS_MODULE_DIR_ . 'arkoninstagram/views/templates/admin/settings.tpl');
//        return $this->form();
//    }

    public function initContent()
    {
        $this->content .= $this->form();
        parent::initContent();
    }

    public function form()
    {
        $number_options = [];

        for($i = 1; $i <= 50; ++$i){
            $number_options[] = ['number' => $i, 'name' => $i];
        }

        $size_options = [];

        for($i = 10; $i <= 40; ++$i){
            $size_options[] = ['size' => $i * 10, 'name' => $i * 10];
        }

        $gap_options = [];

        for($i = 0; $i <= 30; ++$i){
            $gap_options[] = ['gap' => $i, 'name' => $i];
        }

        $display_hooks_options = $this->getDisplayHooks();

        $form = [
            [
                'form' => [
                    'tinymce' => true,
                    'tabs' => [
                        'desktop' => $this->l('Desktop'),
                        'mobile' => $this->l('Mobile')
                    ],
                    'legend' => [
                        'title' => $this->module->displayName,
                        'icon' => 'icon-cogs',
                    ],
                    'input' => [
                        //Display Hooks
                        [
                            'type' => 'select',
                            'tab' => 'desktop',
                            'name' => 'desktop_display_hook',
                            'label' => $this->l('Hook'),
                            'options' => [
                                'query' => $display_hooks_options,
                                'id' => 'id_hook',
                                'name' => 'name'
                            ]
                        ],
                        [
                            'type' => 'select',
                            'tab' => 'mobile',
                            'name' => 'mobile_display_hook',
                            'label' => $this->l('Hook'),
                            'options' => [
                                'query' => $display_hooks_options,
                                'id' => 'id_hook',
                                'name' => 'name'
                            ]
                        ],

                        //Display style
                        [
                            'type' => 'radio',
                            'tab' => 'desktop',
                            'label' => $this->l('Display style'),
                            'name' => 'desktop_display_style',
                            'values' => [
                                [
                                    'id' => 'desktop_display_style_slider',
                                    'value' => 'slider',
                                    'label' => $this->l('Slider')
                                ],
                                [
                                    'id' => 'desktop_display_style_grid',
                                    'value' => 'grid',
                                    'label' => $this->l('Grid')
                                ]
                            ]
                        ],
                        [
                            'type' => 'radio',
                            'tab' => 'mobile',
                            'label' => $this->l('Display style'),
                            'name' => 'mobile_display_style',
                            'values' => [
                                [
                                    'id' => 'mobile_display_style_slider',
                                    'value' => 'slider',
                                    'label' => $this->l('Slider')
                                ],
                                [
                                    'id' => 'mobile_display_style_grid',
                                    'value' => 'grid',
                                    'label' => $this->l('Grid')
                                ]
                            ]
                        ],

                        //Show title
                        [
                            'type' => 'switch',
                            'tab' => 'desktop',
                            'label' => $this->l('Show title'),
                            'name' => 'desktop_show_title',
                            'values' => [
                                [
                                    'id' => 'desktop_show_title_on',
                                    'value' => 1,
                                    'label' => $this->l('ON')
                                ],
                                [
                                    'id' => 'desktop_show_title_off',
                                    'value' => 0,
                                    'label' => $this->l('OFF')
                                ],
                            ]
                        ],
                        [
                            'type' => 'switch',
                            'tab' => 'mobile',
                            'label' => $this->l('Show title'),
                            'name' => 'mobile_show_title',
                            'values' => [
                                [
                                    'id' => 'mobile_show_title_on',
                                    'value' => 1,
                                    'label' => $this->l('ON')
                                ],
                                [
                                    'id' => 'mobile_show_title_off',
                                    'value' => 0,
                                    'label' => $this->l('OFF')
                                ],
                            ]
                        ],

                        //Gallery Title
                        [
                            'type' => 'textarea',
                            'name' => 'desktop_title',
                            'tab' => 'desktop',
                            'label' => $this->l('Title'),
                            'class' => 'rte',
                            'autoload_rte' => 'true'
                        ],
                        [
                            'type' => 'textarea',
                            'name' => 'mobile_title',
                            'tab' => 'mobile',
                            'label' => $this->l('Title'),
                            'class' => 'rte',
                            'autoload_rte' => 'true'
                        ],

                        //Number of fetched images
                        [
                            'type' => 'select',
                            'tab' => 'desktop',
                            'label' => $this->l('Number of images fetched'),
                            'name' => 'desktop_max_images_fetched',
                            'options' => [
                                'query' => $number_options,
                                'id' => 'number',
                                'name' => 'name'
                            ]
                        ],
                        [
                            'type' => 'select',
                            'tab' => 'mobile',
                            'label' => $this->l('Number of images fetched'),
                            'name' => 'mobile_max_images_fetched',
                            'options' => [
                                'query' => $number_options,
                                'id' => 'number',
                                'name' => 'name'
                            ]
                        ],

                        //Number of images displayed at one time
                        [
                            'type' => 'select',
                            'tab' => 'desktop',
                            'label' => $this->l('Number of displayed images'),
                            'name' => 'desktop_images_per_gallery',
                            'options' => [
                                'query' => $number_options,
                                'id' => 'number',
                                'name' => 'name'
                            ]
                        ],
                        [
                            'type' => 'select',
                            'tab' => 'mobile',
                            'label' => $this->l('Number of displayed images'),
                            'name' => 'mobile_images_per_gallery',
                            'options' => [
                                'query' => $number_options,
                                'id' => 'number',
                                'name' => 'name'
                            ]
                        ],

                        //Size of images
                        [
                            'type' => 'select',
                            'tab' => 'desktop',
                            'label' => $this->l('Size of images'),
                            'name' => 'desktop_image_size',
                            'options' => [
                                'query' => $size_options,
                                'id' => 'size',
                                'name' => 'name'
                            ]
                        ],
                        [
                            'type' => 'select',
                            'tab' => 'mobile',
                            'label' => $this->l('Size of images'),
                            'name' => 'mobile_image_size',
                            'options' => [
                                'query' => $size_options,
                                'id' => 'size',
                                'name' => 'name'
                            ]
                        ],

                        //Gap between images
                        [
                            'type' => 'select',
                            'tab' => 'desktop',
                            'label' => $this->l('Gap between images'),
                            'name' => 'desktop_gap',
                            'options' => [
                                'query' => $gap_options,
                                'id' => 'gap',
                                'name' => 'name'
                            ]
                        ],
                        [
                            'type' => 'select',
                            'tab' => 'mobile',
                            'label' => $this->l('Gap between images'),
                            'name' => 'mobile_gap',
                            'options' => [
                                'query' => $gap_options,
                                'id' => 'gap',
                                'name' => 'name'
                            ]
                        ],

                        //Grid row
                        [
                            'type' => 'select',
                            'tab' => 'desktop',
                            'label' => $this->l('Images in row'),
                            'name' => 'desktop_grid_row',
                            'options' => [
                                'query' => $number_options,
                                'id' => 'number',
                                'name' => 'name'
                            ]
                        ],
                        [
                            'type' => 'select',
                            'tab' => 'mobile',
                            'label' => $this->l('Images in row'),
                            'name' => 'mobile_grid_row',
                            'options' => [
                                'query' => $number_options,
                                'id' => 'number',
                                'name' => 'name'
                            ]
                        ],

                        //Grid column
                        [
                            'type' => 'select',
                            'tab' => 'desktop',
                            'label' => $this->l('Images in column'),
                            'name' => 'desktop_grid_column',
                            'options' => [
                                'query' => $number_options,
                                'id' => 'number',
                                'name' => 'name'
                            ]
                        ],
                        [
                            'type' => 'select',
                            'tab' => 'mobile',
                            'label' => $this->l('Images in column'),
                            'name' => 'mobile_grid_column',
                            'options' => [
                                'query' => $number_options,
                                'id' => 'number',
                                'name' => 'name'
                            ]
                        ],
                    ],
//                    'buttons' => [
//                        [
//                            'title' => $this->l('Refresh images'),
//                            'class' => 'btn btn-default pull-right',
//                            'id' => 'refresh',
//                        ]
//                    ],
                    'submit' => [
                        'title' => $this->l('Save'),
                        'class' => 'btn btn-secondary pull-right',
                    ],
                ],
            ],
        ];

        $helper = new HelperForm();

        $settings = new InstagramDisplaySettings(INSTAGRAM_DESKTOP_CONFIG_ID);
        $m_settings = new InstagramDisplaySettings(INSTAGRAM_MOBILE_CONFIG_ID);

        $helper->fields_value = [
            'desktop_display_hook' => $settings->hook,
            'mobile_display_hook' => $m_settings->hook,
            'desktop_title' => $settings->title,
            'mobile_title' => $m_settings->title,
            'desktop_display_style' => $settings->display_style,
            'mobile_display_style' => $m_settings->display_style,
            'desktop_show_title' => $settings->show_title,
            'mobile_show_title' => $m_settings->show_title,
            'desktop_max_images_fetched' => $settings->max_images_fetched,
            'mobile_max_images_fetched' => $m_settings->max_images_fetched,
            'desktop_images_per_gallery' => $settings->images_per_gallery,
            'mobile_images_per_gallery' => $m_settings->images_per_gallery,
            'desktop_image_size' => $settings->image_size,
            'mobile_image_size' => $m_settings->image_size,
            'desktop_gap' => $settings->gap,
            'mobile_gap' => $m_settings->gap,
            'desktop_grid_row' => $settings->grid_row,
            'mobile_grid_row' => $m_settings->grid_row,
            'desktop_grid_column' => $settings->grid_column,
            'mobile_grid_column' => $m_settings->grid_column,
        ];

        return $helper->generateForm($form);
    }

    private function getDisplayHooks(): array
    {
        return DB::getInstance()->executeS('SELECT name AS id_hook, name FROM `' . _DB_PREFIX_ . 'hook` WHERE name LIKE "display%" AND name NOT LIKE "displayAdmin%"');
    }
}