<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateProductRequest;

use App\Product;
use App\User;
use App\Offer;
use App\Comment;
use File;

class ProductController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth:api')
    		->except(['index', 'show']);
    }

    public function index()
    {
    	$products = Product::orderBy('created_at', 'desc')
    		->get();

    	return response()
    		->json([
    			'products' => $products
    		]);
    }

    public function create()
    {
        $form = Product::form();
    	return response()
    		->json([
    			'form' => $form
    		]);
    }

    public function store(CreateProductRequest $request)
    {
    	$offers = [];
        foreach($request->offers as $offer) {
            $offers[] = new Offer($offer);
        }

    	if(!$request->hasFile('image') && !$request->file('image')->isValid()) {
    		return abort(404, 'Image not uploaded!');
    	}

    	$filename = $this->getFileName($request->image);
    	$request->image->move(base_path('public/images'), $filename);

    	$product = new Product($request->only('name', 'description', 'image', 'quantity', 'price'));
    	$product->image = $filename;

    	$request->user()->products()
    		->save($product);

        if ($offers) {
            $product->offers()
            ->saveMany($offers);
        } 

    	return response()
    	    ->json([
    	        'success' => true,
    	        'id' => $product->id,
                'message' => 'You have successfully created product!'
    	    ]);
    }

    private function getFileName($file)
    {
    	return str_random(32).'.'.$file->extension();
    }

    public function show($id)
    {
        $product = Product::with(
                [
                    'user', 
                    'offers', 
                    'comments' => function($q) {
                        return $q
                            ->orderBy('created_at', 'desc')
                            ->limit(2)
                            ->with('user');
                    }
                ]
            )
            ->findOrFail($id);

        // $comment = new Comment();
        // $comment->body = 'Tes nas to';
        // $comment->user_id = 1;
        // $test = $product->comments()->save($comment);

        $form = Comment::form();

        return response()
            ->json([
                'product' => $product,
                'form' => $form
            ]);
    }


    public function edit($id, Request $request)
    {
        $form = $request->user()->products()
            ->with(['offers' => function($query) {
                $query->get(['id', 'name', 'quantity', 'price']);
            }])
            ->findOrFail($id, [
                'id', 'name', 'description', 'image', 'quantity', 'price'
            ]);

        return response()
            ->json([
                'form' => $form
            ]);
    }

    public function update($id, CreateProductRequest $request)
    {
        $product = $request->user()->products()
            ->findOrFail($id);

        $offers = [];
        $offersUpdated = [];

        foreach($request->offers as $offer) {
            if(isset($offer['id'])) {
                Offer::where('product_id', $product->id)
                    ->where('id', $offer['id'])
                    ->update($offer);

                $offersUpdated[] = $offer['id'];
            } else {
                $offers[] = new Offer($offer);
            }
        }

        $product->name = $request->name;
        $product->description = $request->description;
        $product->quantity = $request->quantity;
        $product->price = $request->price;

        // upload image
        if ($request->hasfile('image') && $request->file('image')->isValid()) {
            $filename = $this->getFileName($request->image);
            $request->image->move(base_path('/public/images'), $filename);

            // remove old image
            File::delete(base_path('/public/images/'.$product->image));
            $product->image = $filename;
        }

        $product->save();

        Offer::whereNotIn('id', $offersUpdated)
            ->where('product_id', $product->id)
            ->delete();

        if(count($offers)) {
            $product->offers()->saveMany($offers);
        }

        return response()
            ->json([
                'success' => true,
                'id' => $product->id,
                'message' => 'You have successfully updated product!'
            ]);
    }

    public function destroy($id, Request $request)
    {
        $product = $request->user()->products()
            ->findOrFail($id);

        Offer::where('product_id', $product->id)
            ->delete();

        // remove image
        File::delete(base_path('/public/images/'.$product->image));

        $product->delete();

        return response()
            ->json([
                'success' => true
            ]);
    }

    public function postComment(Product $product, Request $request) {
        $this->validate($request, [
            'body' => 'required|between:10,500'
        ]);

        $comment = new Comment(['body' => $request->body]);
        $comment->user_id = $request->user()->id;

        $comment = $product->comments()->save($comment);

        $comment = Comment::where('id', $comment->id)->with('user')->first();

        return response()
            ->json([
                'success' => true,
                'comment' => $comment
            ]);
    }
}
