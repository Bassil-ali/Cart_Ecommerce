<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Sub_Category;
use App\Models\Parent_Category;
use App\Http\Requests\SubCategory;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;



class SubCategoryController extends Controller
{

    public function __construct()
    {
        //create read update delete
        $this->middleware(['permission:sub_categories_read'])->only('index');
        $this->middleware(['permission:sub_categories_create'])->only('create');
        $this->middleware(['permission:sub_categories_update'])->only('edit');
        $this->middleware(['permission:sub_categories_delete'])->only('destroy');

    }//end of constructor

    public function index(Request $request)
    {
        $sub_categories = Sub_Category::orderBy('parent_category_id')->when($request->search, function ($q) use ($request) {

            return $q->where('name->ar', 'like', '%' . $request->search . '%')
                   ->orWhere('name->en', 'like', '%' . $request->search . '%');

        })->latest()->get();

<<<<<<< HEAD
=======
         

>>>>>>> 3e98f0127e3653c6328c7b663312ee536f6c9346
        return view('dashboard.sub_category.index', compact('sub_categories'));
    }//end of index


    public function create()
    {

        $parent_categories = Parent_Category::get();

        return view('dashboard.sub_category.create',compact('parent_categories'));

    }//end of create

    
    public function store(SubCategory $request)
    {

        $request->validate([
            'name'                  => 'required',
            'name_en'               => 'required',
            'parent_category_id'    => 'required',
            'image'                 => 'required',
        ]);


        $sub_categories = $request->all();
        try {

            $validated = $request->validated();

                $my_categories                     = new Sub_Category();
                $my_categories->name               = ['ar'=> $sub_categories['name'],'en'=> $sub_categories['name_en']];
                $my_categories->user_id            = auth()->user()->id;
                $my_categories->parent_category_id = $request->parent_category_id;

                $file_name = time() . '.' . $request->image->getClientOriginalExtension();


                $request->image->move('uploads/sub_category_images/' , $file_name);
    

                $my_categories->image              = $file_name;
                $my_categories->color_1            = $request->color1;
                $my_categories->color_2            = $request->color2;

                $my_categories->save();

            notify()->success(__('home.added_successfully'));
            return redirect()->route('dashboard.sub_categories.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }

    }//end of store



    public function edit(sub_category $sub_category)
    {
        $parent_categories = Parent_Category::get();

        return view('dashboard.sub_category.edit',compact('sub_category','parent_categories'));
    }//end of edit


    public function update(SubCategory $request, Sub_Category $sub_category)
    {
        
        try {
            
            
            if($request->image){

                $file_name = time() . '.' . $request->image->getClientOriginalExtension();


                $request->image->move('uploads/sub_category_images/' , $file_name);
    
            
                $sub_category->update([

                'name'               => ['ar'=> $request->name,'en'=> $request->name_en],

                'user_id'            => auth()->user()->id,

                'parent_category_id' => $request->parent_category_id,

                'image'              => $file_name,
               
                'color_1'            => $request->color1,

                'color_2'            => $request->color2,
                ]);
            }else{

                $sub_category->update([

                    'name'               => ['ar'=> $request->name,'en'=> $request->name_en],
    
                    'user_id'            => auth()->user()->id,
    
                    'parent_category_id' => $request->parent_category_id,
    
                   
                    'color_1'            => $request->color1,
    
                    'color_2'            => $request->color2,
                    ]);
                }
           
            notify()->success(__('home.updated_successfully'));
            return redirect()->route('dashboard.sub_categories.index');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }

    }//end of update

    

    public function destroy(sub_category $sub_category)
    {

      
        $sub_category->delete();
        notify()->success(__('home.deleted_successfully'));
        return redirect()->route('dashboard.sub_categories.index');
    }//end of destroy

}//end of controller
