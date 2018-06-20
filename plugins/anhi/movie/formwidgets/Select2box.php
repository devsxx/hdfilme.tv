<?php namespace Anhi\Movie\FormWidgets;

use Backend\Classes\FormWidgetBase;

/**
 * select2box Form Widget
 */
class Select2box extends FormWidgetBase
{

    public $modelName;

    public $nameFrom;

    public $multiple = true;

    /**
     * @inheritDoc
     */
    protected $defaultAlias = 'anhi_movie_select2box';

    /**
     * @inheritDoc
     */
    public function init()
    {
        $this->fillFromConfig([
            'modelName',
            'nameFrom',
            'multiple',
        ]);
        // dd($this->modelName);
        $modelPath = 'Anhi\Movie\Models\\' . $this->modelName;
        $this->model = new $modelPath;
    }

    /**
     * @inheritDoc
     */
    public function render()
    {
        $this->prepareVars();
        return $this->makePartial('select2box');
    }

    function getSaveValue ($value)
    {
        if ($this->multiple)
            return $this->handleMultiValues($value);

        return $this->handleSingleValue($value);
        
    }

    private function handleMultiValues ($value)
    {
        $result = [];

        if (is_array($value))
        {

            foreach ($value as $id)
            {
                if (!is_numeric($id))
                {
                    $name = $id;
                    
                    $item = $this->model->where($this->nameFrom, $name)->first();

                    if (!$item)
                    {
                        $item = new $this->model;
                        $item->{$this->nameFrom} = $name;
                        $item->save();
                    }

                    $result[] = $item->id;
                }
                else
                {
                    $result[] = $id;
                }
            }
        }
        
        return $result;
    }

    function handleSingleValue ($value)
    {

        $newValue = $value;

        if (!is_numeric($value))
        {
            $item = $this->model->where($this->nameFrom, $value)->first();

            if (!$item)
            {
                $item = new $this->model;
                $item->{$this->nameFrom} = $value;
                $item->save();
            }

            $newValue = $item->id;
        }

        return $newValue;
    }

    function prepareVars ()
    {
        $this->vars['name'] = $this->formField->getName() .  ($this->multiple ? '[]' : '');

        $values = $this->getLoadValue();

        $this->vars['multiple'] = $this->multiple;

        if ($this->multiple)
            $model = $this->model->whereIn('id', $values);
        else
            $model = $this->model->where('id', $values);

        $this->vars['list'] = $model->lists($this->nameFrom, 'id');

    }


    /**
     * @inheritDoc
     */
    public function loadAssets()
    {
        $this->addCss('css/select2box.css', 'Anhi.Movie');
        $this->addJs('js/select2box.js', 'Anhi.Movie');
    }
}
