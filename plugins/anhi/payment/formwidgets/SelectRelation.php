<?php namespace Anhi\Payment\FormWidgets;

use Backend\Classes\FormWidgetBase;

/**
 * SelectRelation Form Widget
 */
class SelectRelation extends FormWidgetBase
{

    public $valueField, $displayField, $modelClass;

    /**
     * @inheritDoc
     */
    protected $defaultAlias = 'anhi_payment_select_relation';

    /**
     * @inheritDoc
     */
    public function init()
    {
        $this->fillFromConfig([
            'valueField',
            'displayField',
            'modelClass'
        ]);
    }

    /**
     * @inheritDoc
     */
    public function render()
    {
        $this->prepareVars();
        return $this->makePartial('selectrelation');
    }

    /**
     * Prepares the form widget view data
     */
    public function prepareVars()
    {
        $this->vars['name'] = $this->formField->getName();
        $this->vars['list'] = (new $this->modelClass)->all()->lists($this->displayField, $this->valueField);
        $this->vars['value'] = $this->getLoadValue();
        $this->vars['model'] = $this->model;
    }

    /**
     * @inheritDoc
     */
    public function loadAssets()
    {
        $this->addCss('css/selectrelation.css', 'Anhi.Payment');
        $this->addJs('js/selectrelation.js', 'Anhi.Payment');
    }

    /**
     * @inheritDoc
     */
    public function getSaveValue($value)
    {
        return $value;
    }
}
