<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CategoryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CategoryController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ReorderOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\InlineCreateOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(Category::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/category');
        CRUD::setEntityNameStrings(__('Category'), __('Categories'));
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */

        $this->crud->addColumn([
            'name' => 'name',
            'type' => 'text',
            'label' => 'Заголовок',
        ]);
        $this->crud->addColumn([
            'name'  => 'parent', // name of relationship method in the model
            'type'  => 'relationship',
            'label' => 'Родительская к-ия', // Table column heading
        ]);
        $this->crud->addColumn([
            'name' => 'is_active',
            'type' => 'check',
            'label' => 'Активно',
        ]);

        if (!$this->crud->getRequest()->has('order')) {
            $this->crud->orderBy('parent_id', 'ASC')->orderBy('id', 'ASC');
        }
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(CategoryRequest::class);

        $this->crud->addField([
            'name'  => 'name',
            'type'  => 'text',
            'label' => 'Заголовок',
        ]);

        $this->crud->addField([
            'name'  => 'slug',
            'type'  => 'text',
            'label' => 'SLUG (URL)',
        ]);
        $this->crud->addField([
            'name'  => 'description',
            'type'  => 'textarea',
            'label' => 'Описание',
        ]);

        $this->crud->addField([
            'label' => 'Родитель',
            'type' => 'select',
            'name' => 'parent_id',
            'entity' => 'parent',
            'attribute' => 'name',
        ]);

        $this->crud->addField([
            'label' => "Изображение",
            'name' => "image",
            'type' => 'image',
            'crop' => false, // set to true to allow cropping, false to disable
            'aspect_ratio' => 0, // ommit or set to 0 to allow any aspect ratio
            'disk'  => 'public', // in case you need to show images from a different disk
//            'prefix'    => 'uploads/categories' // in case your db value is only the file name (no path), you can use this to prepend your path to the image src (in HTML), before it's shown to the user;
        ]);

        $this->crud->addField([
            'name'  => 'is_active',
            'type'  => 'checkbox',
            'label' => 'Активно',
        ]);
        $this->crud->addField([
            'name'  => 'show_in_menu',
            'type'  => 'checkbox',
            'label' => 'Показывать в меню',
        ]);

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    protected function setupReorderOperation()
    {
        CRUD::set('reorder.label', 'name');
        CRUD::set('reorder.max_level', 2);
    }
}
