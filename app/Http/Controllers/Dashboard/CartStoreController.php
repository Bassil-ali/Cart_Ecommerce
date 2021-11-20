<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\CartStoreRequests;
use App\Models\CartStore;
use App\Models\Product;
use App\Models\Notify;
use App\Models\Cliant;
use App\Models\Sub_Category;
use Illuminate\Http\Request;
use App\Jobs\NotifyJob;


class CartStoreController extends Controller
{

    public function __construct()
    {
        //create read update delete
        $this->middleware(['permission:carts_store_read'])->only('index');
        $this->middleware(['permission:carts_store_create'])->only('create');
        $this->middleware(['permission:carts_store_update'])->only('edit');
        $this->middleware(['permission:carts_store_delete'])->only('destroy');

    } //end of constructor

    public function index(Request $request)
    {
        $carts_store = CartStore::when($request->search, function ($q) use ($request) {

            // return $q->HasTranslations('name', '%' . $request->search . '%');
            return $q->where('users_id', 'like', '%' . $request->search . '%')
                ->orWhere('cart_name->ar', 'like', '%' . $request->search . '%')
                ->orWhere('cart_name->en', 'like', '%' . $request->search . '%')
                ->orWhere('cart_code', 'like', '%' . $request->search . '%')
                ->orWhere('used', 'like', '%' . $request->search . '%');

        })->latest()->paginate(100);

        return view('dashboard.carts_store.index', compact('carts_store'));
    } //end of index

    public function create()
    {
        $products = Product::all();
        
        $sub_categorys = Sub_Category::all();

        return view('dashboard.carts_store.create', compact('products','sub_categorys'));
    } //end of create

    public function store(CartStoreRequests $request, Product $products)
    {


        // try {
        $all = Product::where('id', $request->products_id)->pluck('quantity')->first();


        $sum = $all + 1;


        $dd = Product::where('id', $request->products_id)->update(['quantity' => $sum]);

      
        $request_all = $request->all();

        $carts = new CartStore();

        $carts->cart_name      = ['ar' => $request_all['cart_name'], 'en' => $request_all['cart_name_en']];
        $carts->cart_code      = $request->cart_code;
        $carts->users_id       = auth()->user()->id;
        $carts->products_id    = $request->products_id;
        $carts->sub_category_id = $request->sub_category_id;

        $carts->save();

        $notifys = Notify::where('cart_id', '=',$request->products_id)->distinct()->get();
        


        if($notifys->count() > 1){

        foreach($notifys as $notify){

           
            $product = Product::where('id','=',$notify->cart_id)->first();


            $cliants = Cliant::where('id',$notify->cliant_id)->where('emailVerified',1)->get();


        }
     
        dispatch(new NotifyJob($cliants,$product));


    }
        // dd($carts);

        notify()->success(__('home.added_successfully'));
        return redirect()->route('dashboard.carts_store.index');

        // } catch (\Exception $e) {
        //     return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        // } //end try

    } //end of store

    public function edit(CartStore $cartStore,$id)
    {
        $products = Product::all();
        $cartStore = CartStore::find($id);
        $sub_categorys = Sub_Category::all();

        return view('dashboard.carts_store.edit',compact('cartStore','products','sub_categorys'));
    } //end of edit

    public function update(CartStoreRequests $request, CartStore $cartStore,$id)
    {

        


            $cart   = CartStore::where('products_id',$id)->first();

            $prudoct =  Product::where('id', $cart->product_id)->first();


            if ($cart->product_id == $request->products_id) {

            } else {

                //old product quantity
                $cart   = CartStore::where('products_id',$id)->first();

                $prudoct =  Product::where('id', $cart->product_id)->pluck('quantity')->first();

                $sub = $prudoct - 1;
        
                 Product::where('id', $cart->product_id)->update(['quantity' => $sub]);



                 //new product quantity
                $all = Product::where('id', $request->products_id)->pluck('quantity')->first();


                $sum = $all + 1;
        
        
                 Product::where('id', $request->products_id)->update(['quantity' => $sum]);



        
               
            }

            $cartStore = CartStore::find($id);

            $cartStore->update([

                    'cart_name.'      => ['ar'=> $request->cart_name,      'en'=> $request->cart_name_en],
                    'cart_code'       => $request->cart_code,
                    'users_id'        => auth()->user()->id,
                    'products_id'     => $request->products_id,
                    'sub_category_id' => $request->sub_category_id,


                ]);

            notify()->success(__('home.updated_successfully'));
            return redirect()->route('dashboard.carts_store.index');

       

    } //end of update

    public function destroy(CartStore $cartStore , $id)
    {
        $all = Product::where('id', $id)->pluck('quantity')->first();

        $dd = Product::where('id', $id)->update(['quantity' => $all - 1]);

        $cartStore = CartStore::find($id);
        $cartStore->delete();
        notify()->success(__('home.deleted_successfully'));
        return redirect()->route('dashboard.carts_store.index');
    } //end of destroy

} //end of Controller
