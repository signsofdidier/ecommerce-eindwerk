<?php

namespace App\Helpers;

use App\Models\Product;

$company = app()->has('current_company') ? app()->make('current_company') : null;

class CartManagement {


    // Voeg een product toe aan de winkelwagen met tenant-controle
    static public function addItemToCart($product_id) {
        $company = app()->has('current_company') ? app()->make('current_company') : null;
        $cart_items = self::getCartItemsFromSession();

        // Verwijder items van andere tenants als we een nieuwe toevoegen
        if ($company) {
            $cart_items = array_filter($cart_items, function($item) use ($company) {
                $product = Product::find($item['product_id']);
                return $product && $product->company_id == $company->id;
            });
        }

        $existing_item = null;
        foreach($cart_items as $key => $item) {
            if($item['product_id'] == $product_id) {
                $existing_item = $key;
                break;
            }
        }

        $product = Product::when($company, function($query) use ($company) {
            $query->where('company_id', $company->id);
        })
            ->find($product_id, ['id', 'name', 'price', 'images', 'company_id']);

        if(!$product) {
            self::saveCartItemsToSession($cart_items);
            return count($cart_items);
        }

        if ($existing_item !== null) {
            $cart_items[$existing_item]['quantity']++;
            $cart_items[$existing_item]['total_amount'] = $cart_items[$existing_item]['quantity'] *
                $cart_items[$existing_item]['unit_amount'];
        } else {
            $cart_items[] = [
                'product_id' => $product_id,
                'name' => $product->name,
                'quantity' => 1,
                'unit_amount' => $product->price,
                'total_amount' => $product->price,
                'image' => $product->images[0] ?? null,
                'company_id' => $product->company_id,
            ];
        }

        self::saveCartItemsToSession($cart_items);
        return count($cart_items);
    }

    // Voeg product toe met specifieke hoeveelheid
    static public function addItemToCartWithQuantity($product_id, $quantity) {
        $company = app()->has('current_company') ? app()->make('current_company') : null;
        $cart_items = self::getCartItemsFromSession();

        if ($company) {
            $cart_items = array_filter($cart_items, function($item) use ($company) {
                $product = Product::find($item['product_id']);
                return $product && $product->company_id == $company->id;
            });
        }

        $existing_item = null;
        foreach($cart_items as $key => $item) {
            if($item['product_id'] == $product_id) {
                $existing_item = $key;
                break;
            }
        }

        $product = Product::when($company, function($query) use ($company) {
            $query->where('company_id', $company->id);
        })
            ->find($product_id, ['id', 'name', 'price', 'images', 'company_id']);

        if(!$product) {
            self::saveCartItemsToSession($cart_items);
            return count($cart_items);
        }

        if ($existing_item !== null) {
            $cart_items[$existing_item]['quantity'] = $quantity;
            $cart_items[$existing_item]['total_amount'] = $cart_items[$existing_item]['quantity'] *
                $cart_items[$existing_item]['unit_amount'];
        } else {
            $cart_items[] = [
                'product_id' => $product_id,
                'name' => $product->name,
                'quantity' => $quantity,
                'unit_amount' => $product->price,
                'total_amount' => $product->price * $quantity,
                'image' => $product->images[0] ?? null,
                'company_id' => $product->company_id,
            ];
        }

        self::saveCartItemsToSession($cart_items);
        return count($cart_items);
    }

    // Verwijder een product uit de winkelwagen
    static public function removeCartItem($product_id) {
        $cart_items = self::getCartItemsFromSession();
        foreach($cart_items as $key => $item) {
            if($item['product_id'] == $product_id) {
                unset($cart_items[$key]);
            }
        }

        $cart_items = array_values($cart_items);
        self::saveCartItemsToSession($cart_items);
        return $cart_items;
    }

    // Sla winkelwagen items op in sessie
    static public function saveCartItemsToSession($cart_items) {
        session()->put('cart_items', $cart_items);
    }

    // Maak winkelwagen leeg
    static public function clearCartItems() {
        session()->forget('cart_items');
    }

    // Haal winkelwagen items op uit sessie
    static public function getCartItemsFromSession() {
        return session()->get('cart_items', []);
    }

    // Verhoog hoeveelheid van een product
    static public function incrementQuantityToCartItem($product_id) {
        $cart_items = self::getCartItemsFromSession();
        foreach($cart_items as $key => $item) {
            if($item['product_id'] == $product_id) {
                $cart_items[$key]['quantity']++;
                $cart_items[$key]['total_amount'] = $cart_items[$key]['quantity'] *
                    $cart_items[$key]['unit_amount'];
                break;
            }
        }
        self::saveCartItemsToSession($cart_items);
    }

    // Verlaag hoeveelheid van een product
    static public function decrementQuantityToCartItem($product_id) {
        $cart_items = self::getCartItemsFromSession();
        foreach($cart_items as $key => $item) {
            if($item['product_id'] == $product_id && $item['quantity'] > 1) {
                $cart_items[$key]['quantity']--;
                $cart_items[$key]['total_amount'] = $cart_items[$key]['quantity'] *
                    $cart_items[$key]['unit_amount'];
                break;
            }
        }
        self::saveCartItemsToSession($cart_items);
    }

    // Bereken totaalbedrag
    static public function calculateGrandTotal($items) {
        return array_sum(array_column($items, 'total_amount'));
    }

    // Valideer winkelwagen items tegen huidige tenant
    static public function validateCartItems() {
        $company = app()->has('current_company') ? app()->make('current_company') : null;
        if (!$company) return;

        $cart_items = self::getCartItemsFromSession();
        $valid_items = [];

        foreach ($cart_items as $item) {
            $product = Product::where('id', $item['product_id'])
                ->where('company_id', $company->id)
                ->first();

            if ($product) {
                $valid_items[] = $item;
            }
        }

        self::saveCartItemsToSession($valid_items);
        return $valid_items;
    }
}
