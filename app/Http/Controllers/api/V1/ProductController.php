<?php

namespace App\Http\Controllers\api\V1;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
     * @var Product
     */
    private $product;
    private $totalPage = 5;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products= $this->product->paginate($this->totalPage);
        return response()->json(['data' => $products]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validate = validator($data, $this->product->rules());

        if($validate->fails()){
            $messages = $validate->messages();
            return response()->json(['validate.error', $messages], 422);
        }

        if(!$insert = $this->product->create($request->all())){
            return response()->json(['error' => 'erro_insert'], 500);
        }

        return response()->json(['data' => $insert], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!$product = $this->product->find($id)){
            return response()->json(['error' => 'product_not_found'], 404);
        }

        return response()->json(['data' => $product]);
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
        $data = $request->all();

        $validate = validator($data, $this->product->rules($id));

        if($validate->fails()){
            $messages = $validate->messages();
            return response()->json(['validate.error', $messages], 422);
        }

        if(!$product = $this->product->find($id)){
            return response()->json(['error' => 'product_not_found'], 404);
        }

        if(!$update = $product->update($data)){
            return response()->json(['error' => 'product_not_update'], 500);
        }

        return response()->json(['response' => $update]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!$product = $this->product->find($id)){
            return response()->json(['error' => 'product_not_found'], 404);
        }

        if(! $delete = $product->delete()){
            return response()->json(['error' => 'product_not_delete'], 500);
        }

        return response()->json(['response' => $delete]);
    }

    public function search(Request $request)
    {
        $data = $request->all();

        $validate = validator($data, $this->product->rulesSearch());

        if($validate->fails()){
            $messages = $validate->messages();
            return response()->json(['validate.error', $messages], 422);
        }

        $products = $this->product->serach($data,$this->totalPage);

        return response()->json(['data' => $products]);
    }
}
