<?php namespace Anhi\Movie\FormWidgets;

use Backend\Classes\FormWidgetBase;

use Anhi\Movie\Models\Country as CountryModel;

/**
 * Country Form Widget
 */
class Country extends FormWidgetBase
{

    use Traits\SaveValue;
    
    /**
     * @inheritDoc
     */
    protected $defaultAlias = 'anhi_movie_country';

    /**
     * @inheritDoc
     */
    public function init()
    {
        $this->model = new CountryModel;
        $this->fieldName = 'country_name';
    }

    /**
     * @inheritDoc
     */
    public function render()
    {
        $this->prepareVars();
        return $this->makePartial('country');
    }

    /**
     * Prepares the form widget view data
     */
    public function prepareVars()
    {
        $this->vars['name'] = $this->formField->getName() ;
        $this->vars['value'] = $this->getLoadValue();
        $this->vars['model'] = $this->model;
    }

    /**
     * @inheritDoc
     */
    public function loadAssets()
    {
        $this->addCss('css/country.css', 'Anhi.Movie');
        $this->addJs('js/country.js', 'Anhi.Movie');
    }

    /**
     * @inheritDoc
     */
    public function getSaveValue($value)
    {
        return $value;
    }
}
