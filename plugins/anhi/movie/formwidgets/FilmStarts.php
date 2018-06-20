<?php namespace Anhi\Movie\FormWidgets;

use Backend\Classes\FormWidgetBase;

/**
 * FilmStarts Form Widget
 */
class FilmStarts extends FormWidgetBase
{
    /**
     * @inheritDoc
     */
    protected $defaultAlias = 'anhi_movie_film_starts';

    /**
     * @inheritDoc
     */
    public function init()
    {
        
    }

    /**
     * @inheritDoc
     */
    public function render()
    {
        $this->prepareVars();
        return $this->makePartial('filmstarts');
    }

    /**
     * Prepares the form widget view data
     */
    public function prepareVars()
    {
        $this->vars['name'] = $this->formField->getName();
        $this->vars['value'] = $this->getLoadValue();
        $this->vars['model'] = $this->model;
    }

    /**
     * @inheritDoc
     */
    public function loadAssets()
    {
        $this->addCss('css/filmstarts.css', 'Anhi.Movie');
        $this->addJs('js/filmstarts.js?v=1.2', 'Anhi.Movie');
    }

    /**
     * @inheritDoc
     */
    public function getSaveValue($value)
    {
        return $value;
    }
}
