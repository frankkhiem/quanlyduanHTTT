<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class AdminCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        //
        $keywork = $request->q;
        if( $keywork ) {
            $categories = Category::where('name', 'like', '%'.$keywork.'%')
                                    ->orWhere('short_desc', 'like', '%'.$keywork.'%')
                                    ->orWhere('full_desc', 'like', '%'.$keywork.'%')
                                    ->orderBy('id', 'desc')
                                    ->paginate(10);
        }
        else {
            $categories = Category::orderBy('id', 'desc')->paginate(10);
        }
        return view('admin.category.adminCategory', 
                    [
                        'categories' => $categories,
                    ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.category.createCategory');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        Category::create( $request->all() );
        return redirect()->route('adminCategory.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        return view('admin.category.editCategory',
                    [
                        'category' => Category::findOrFail($id),
                    ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        Category::where('id', $id)->update([
                                        'name' => $request->name,
                                        'short_desc' => $request->short_desc,
                                        'full_desc' => $request->full_desc
                                    ]);
        return redirect()->route('adminCategory.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //        
        $productsOnCategory = Product::where('category_id', $id)->get();
        foreach ($productsOnCategory as $product) {
            $product->category_id = null;
            $product->save();
        }
        Category::where('id', $id)->delete();
        return 'hi';
        // return redirect()->route('adminCategory.index');
    }
}
