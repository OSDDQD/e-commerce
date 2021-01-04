<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ItemRequest;
use App\Models\Item;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CategoryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ItemController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
//    use \Backpack\CRUD\app\Http\Controllers\Operations\ReorderOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\InlineCreateOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(Item::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/item');
        CRUD::setEntityNameStrings(__('Item'), __('Items'));
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
            'name'  => 'category', // name of relationship method in the model
            'type'  => 'relationship',
            'label' => 'Категория', // Table column heading
        ]);
        $this->crud->addColumn([
            'name' => 'is_active',
            'type' => 'check',
            'label' => 'Активно',
        ]);

        if (!$this->crud->getRequest()->has('order')) {
            $this->crud->orderBy('position', 'ASC')->orderBy('id', 'ASC');
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
        CRUD::setValidation(ItemRequest::class);

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
            'label' => 'Категория',
            'type' => 'select',
            'name' => 'category_id',
            'entity' => 'category',
            'attribute' => 'name',
        ]);

        $this->crud->addField([   // Upload
            'name'      => 'images',
            'label'     => 'Изображения',
            'type'      => 'upload_multiple',
            'upload'    => true,
            'disk'      => 'public', // if you store files in the /public folder, please omit this; if you store them in /storage or S3, please specify it;
            // optional:
//            'temporary' => 10 // if using a service, such as S3, that requires you to make temporary URLs this will make a URL that is valid for the number of minutes specified
        ]);

        $this->crud->addField([
            'name'  => 'position',
            'type'  => 'number',
            'label' => 'Позиция',
        ]);
        $this->crud->addField([
            'name'  => 'is_digital',
            'type'  => 'checkbox',
            'label' => 'Цифровой товар',
        ]);
        $this->crud->addField([
            'name'  => 'is_active',
            'type'  => 'checkbox',
            'label' => 'Активно',
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
