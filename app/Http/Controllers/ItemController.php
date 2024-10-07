<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use Illuminate\Http\Request;
use Storage;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function search(Request $request)
    {
        // if ($request) {
        //     $item = Item::orWhere('name', 'LIKE', "%{$request->name}%")
        //         ->orWhere('price', $request->price)
        //         ->orWhere('stock', $request->stock)
        //         ->get();

        //     if ($item->isEmpty()) {
        //         return response()->json(['message' => 'Not Found Item'], 404);
        //     }

        //     return response()->json(['message' => 'success', 'item' => $item], 200);
        // }

        // return response()->json(['message' => 'Not Found Item'], 404);


        if ($request->hasAny(['name', 'price', 'stock'])) {
            $item = Item::when($request->name, function ($query, $name) {
                return $query->orWhere('name', 'LIKE', "%{$name}%");
            })
                ->when($request->price, function ($query, $price) {
                    return $query->orWhere('price', $price);
                })
                ->when($request->stock, function ($query, $stock) {
                    return $query->orWhere('stock', $stock);
                })
                ->get();

            if ($item->isEmpty()) {
                return response()->json(['message' => 'Not Found Item'], 404);
            }

            return response()->json(['message' => 'success', 'item' => $item], 200);
        }

        return response()->json(['message' => 'Not Found Item'], 404);

    }

    public function index()
    {
        // method one
        // $item = Item::with('category')->get();

        //method two
        $item = Item::with('category')->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'price' => $item->price,
                'stock' => $item->stock,
                'description' => $item->description,
                'status' => $item->status,
                'category' => $item->category->name
            ];
        });

        return response()->json(['message' => 'success', 'data' => $item], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreItemRequest $request)
    {

        $images = [];

        if ($request->images) {
            foreach ($request->file('images') as $file) {
                $newName = "item_image" . uniqid() . "." . $file->extension();
                $file->storeAs('public/item_images', $newName);
                $images[] = $newName;
            }
        }

        // Create the item record
        $item = new Item();
        $item->name = $request->name;
        $item->price = $request->price;
        $item->stock = $request->stock;
        $item->description = $request->description;
        $item->status = $request->status;
        $item->category_id = $request->category_id;
        $item->images = json_encode($images);
        $item->save();

        return response()->json(['message' => 'success'], 200);
    }



    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        return response()->json(['message' => 'success', 'data' => $item], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateItemRequest $request, Item $item)
    {
        $item->name = $request->name;
        $item->price = $request->price;
        $item->stock = $request->stock;
        $item->description = $request->description;
        $item->status = $request->status;
        $item->category_id = $request->category_id;

        $images = [];

        if ($request->images) {
            foreach ($request->file('images') as $file) {
                $newName = "item_image" . uniqid() . "." . $file->extension();
                $file->storeAs('public/item_images', $newName);
                $images[] = $newName;
            }
            $item->images = json_encode($images);
        }

        $item->update();

        return response()->json(['message' => 'update success'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        if ($item) {

            $images = json_decode($item->images);
            if ($images) {
                foreach ($images as $image) {
                    Storage::delete('public/item_images/' . $image);
                }
            }

            $item->delete();

            return response()->json(['message' => 'Item delete success'], 200);
        }
    }
}
