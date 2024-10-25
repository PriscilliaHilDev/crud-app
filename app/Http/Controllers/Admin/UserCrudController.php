<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Validation\Rule;
use App\Http\Requests\UserRequest;
use Spatie\Permission\Models\Role;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class UserCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class UserCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\User::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/user');
        CRUD::setEntityNameStrings('user', 'users');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::setFromDb(); // set columns from db columns.
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(UserRequest::class);
        CRUD::setFromDb(); // set fields from db columns.
        
        CRUD::field([   // Checklist
            'label'     => 'Roles',
            'type'      => 'checklist',
            'name'      => 'roles',
            'entity'    => 'roles',
            'attribute' => 'name',
            'model'     => "Spatie\Permission\Models\Role",
            'pivot'     => true,
            'show_select_all' => true, // default false
            // 'number_of_columns' => 3,
        ]);
        
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $user = CRUD::getCurrentEntry(); // Récupère l'entrée actuelle (l'utilisateur)
    
        // Configurer la validation pour la requête de mise à jour
        CRUD::setValidation(UserRequest::class); // Cela configure la validation par défaut
    
        // Cela ne fonctionne pas directement avec `setValidation`, utilisez plutôt `rules` dans UserRequest
        $rules = [
            'email' => [Rule::unique('users', 'email')->ignore($user->id), 'email:rfc', 'max:255'],
        ];
    
        // Vous pouvez définir les règles de validation dynamiquement si nécessaire
        $this->crud->getRequest()->validate($rules);
        
        CRUD::setFromDb(); // Set fields from db columns.
        CRUD::field('password')->type('password')->attributes(['placeholder' => '********']);
        CRUD::field([   // Checklist
            'label'     => 'Roles',
            'type'      => 'checklist',
            'name'      => 'roles',
            'entity'    => 'roles',
            'attribute' => 'name',
            'model'     => "Spatie\Permission\Models\Role",
            'pivot'     => true,
            'show_select_all' => true, // default false
            // 'number_of_columns' => 3,
        ]);

    }
    
}
