<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Buy;
use App\Models\Sell;
use App\Models\Type;
use App\Models\User;
use App\Models\Check;
use App\Models\Extra;
use App\Models\Stock;
use App\Models\Client;
use App\Models\Wallet;
use App\Models\BuyUser;
use App\Models\Company;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Receipt;
use App\Models\Producer;
use App\Models\Provider;
use App\Models\StockOut;
use App\Models\Supplier;
use App\Models\Attachment;
use App\Models\BuyProduct;
use App\Models\EntryStock;
use App\Models\StockEntry;
use App\Models\TypeWallet;
use App\Models\ProductSell;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use App\Models\StockTransfer;
use App\Models\FinancialInput;
use App\Models\SellStockEntry;
use App\Models\FinancialOutput;
use App\Models\FinancialTransfer;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return redirect(route('companies.index'));
    }

    public function emptyDash(){
        return view('dashboards.empty');
    }

    public function dashboard(){
        $company = Company::findOrFail(request()->company_id);
        $products = Product::where('company_id', request()->company_id)->orderBy('id', 'desc')->limit(5)->get();
        $buyProducts = Buy::where('company_id', request()->company_id)->orderByDesc('id')->limit(5)->get();

        if(count($buyProducts) > 0)
            $totalBuys = $buyProducts->sum('total');
        else
            $totalBuys = 0;

        foreach($buyProducts as $buy){
            $stockEntries = StockEntry::join('buy_product', 'buy_product.id', '=', 'stock_entries.buy_product_id')
                ->where('buy_product.buy_id', $buy->id)
                ->where('stock_entries.is_gain', false)
                ->select('stock_entries.expected_amount', 'stock_entries.amount')
                ->orderBy('stock_entries.id', 'desc')
                ->get();
            $payments = $buy->financialOutputs;
            $totalBuyProducts = 0;
            $totalPriceProducts = 0;

            foreach($buy->products->where('pivot.deleted_at', '==', null) as $product){
                $totalBuyProducts += $product->pivot->quantity;
                $totalPriceProducts += $product->pivot->price * $product->pivot->quantity ;
            }

            if($totalBuyProducts == $stockEntries->sum('amount') && $totalPriceProducts == $payments->sum('value'))
                $buy->status = 'Ok';
            else
                $buy->status = 'Pendente';
        }

        $sellProducts = Sell::where('company_id', request()->company_id)->orderByDesc('id')->limit(5)->get();
        if(count($buyProducts) > 0){
            $totalSells = $sellProducts->sum('total');
        }
        else{
            $totalSells = 0;
        }

        foreach($sellProducts as $sell){
            $stockOuts = StockOut::join('product_sell', 'product_sell.id', '=', 'stock_outs.product_sell_id')
                ->where('product_sell.sell_id', $sell->id)
                ->where('stock_outs.is_lost', false)
                ->select('stock_outs.*')
                ->orderBy('stock_outs.id', 'desc')
                ->get();

            $totalSellProducts = 0;
            $totalPriceProducts = 0;
            $receipts = $sell->financialInputs;

            foreach($sell->products->where('pivot.deleted_at', '==', null) as $product){
                $totalSellProducts += $product->pivot->quantity;
                $totalPriceProducts += $product->pivot->price * $product->pivot->quantity ;
            }

            // if($totalSellProducts == $stockOuts->sum('amount') && $totalPriceProducts == $receipts->sum('value') && $sell->manifest)
            if($totalSellProducts == $stockOuts->sum('amount') && $sell->discharge_date != null && $sell->manifest)
                $sell->status = 'Ok';
            else
                $sell->status = 'Pendente';
        }

        if(request()->first_day){
            $first_dates = explode('?', request()->first_day);
            $second_dates = explode('=', $first_dates[1]);
            $first_day = Carbon::createFromFormat('Y-m-d H', $first_dates[0] . 00);
            $second_day = Carbon::createFromFormat('Y-m-d H', $second_dates[1] . 24);
        }else{
            $first_day = Carbon::createFromFormat('Y-m-d H', Carbon::now()->year . '-01-01' . 00);
            $second_day = Carbon::tomorrow();
        }

        $manifests = [];
        $sells = Sell::where('company_id', request()->company_id)
            ->where('manifest', true)
            ->where('created_at', '>=', $first_day)
            ->where('created_at', '<=', $second_day)
            ->orderByDesc('id')->get();

        foreach($sells as $sell){
            $netProfit = 0;

            if($sell->manifest){
                $buyTotal = 0;
                $buyPrice = 0;
                $extraPrice = 0;
                $numberOfProducts = 0;
                $sellProductsCounter = 0;
                $buyDiscount = 0;
                $buyAddition = 0;
                $buyExtras = [];
                $buyExtrasProfit = [];
                
                foreach($sell->products->where('pivot.deleted_at', null) as $product){
                    if($product->pivot->stock_entry_id){
                        $stockEntryId = $product->pivot->stock_entry_id;
                        $stockEntry = StockEntry::find($stockEntryId);
                        if($stockEntry && !$stockEntry->is_gain){
                            $buy = Buy::find($stockEntry->buy_id);
        
                            if($stockEntry->buyProduct){
                                if($stockEntry->buyProduct->quantity == 0)
                                    $priceBuy = 0;
                                else
                                    $priceBuy = $stockEntry->buyProduct->price / $stockEntry->buyProduct->quantity;
        
                                $totalBuy = 0;
                                foreach($buy->products->where('pivot.deleted_at', '==', null) as $p)
                                    $totalBuy += $p->pivot->quantity;
        
                                foreach($buy->extras as $extra){
                                    if($totalBuy != 0)
                                        $extra->priceNew = $extra->price / $totalBuy * $product->pivot->quantity;
                                    else
                                        $extra->priceNew = $extra->price;
        
                                    $buyExtras[] = $extra;
                                    $buyExtrasProfit[] = $extra;
                                }
        
                                $extrasBuy = $buy->extras->sum('price');
                            }
                            else{
                                $stockEntry->amount == 0 ? $priceBuy = 0 : $priceBuy = $stockEntry->total_price / $stockEntry->amount;
                            }
        
                            $buyTotal += $priceBuy * $product->pivot->quantity;
        
                            foreach($buy->stockEntries as $buyStockEntry)
                                $numberOfProducts += $buyStockEntry->amount;
        
                            if($buy->products->sum('pivot.quantity') != 0 && $buy->discount != 0)
                                $discount = $buy->discount / $buy->products->sum('pivot.quantity') * $product->pivot->quantity;
                            else
                                $discount = $buy->discount;
        
                            if($buy->products->sum('pivot.quantity') != 0 && $buy->addition != 0)
                                $addition = $buy->addition / $buy->products->sum('pivot.quantity') * $product->pivot->quantity;
                            else
                                $addition = $buy->addition;
        
                            $buyDiscount += $discount;
                            $buyAddition += $addition;
                        }
                        else{
                            if($stockEntry && $stockEntry->is_gain){
                                if($stockEntry->productStock->product_id == $product->id){
                                    $buyTotal += $stockEntry->total_price;
                                    $numberOfProducts += $stockEntry->amount;
                                }
                            }
                        }
                    }
                    $sellProductsCounter += $product->pivot->quantity;
                }
        
                $netProfit = $sell->total - $sell->discount + $sell->addition - collect($buyExtrasProfit)->sum('priceNew') - $buyTotal + $buyDiscount - $buyAddition - $sell->extras->sum('price');
                $manifests[] = [
                    "date" => $sell->readable_created_at,
                    "orderDate" => $sell->created_at,
                    "manifest" => $sell->id,
                    "client" => $sell->client->name,
                    "grossValue" => $sell->total,
                    "netProfit" => $netProfit,
                    "%" => $sell->total == 0 ? 0 : (($netProfit/$sell->total) * 100),
                    "clientId" => $sell->client->id,
                ];
            }
        }

        return view('dashboards.company', compact('products', 'buyProducts', 'totalBuys', 'sellProducts', 'totalSells', 'manifests', 'first_day', 'second_day', 'company'));
    }

    public function dashboardBuy($companyId, $id)
    {
        $buy = Buy::where('id', $id)->with(['financialOutputs' => function($query){
            $query->where('is_withdraw', false);
        }])->first();

        $attachments = Attachment::where('fk_id', $id)->where('table_name', 'buys')->get();

        $payments = $buy->financialOutputs;
        $products = $buy->products->where('pivot.deleted_at', '==', null);
        $allProducts = Product::where('company_id', request()->company_id)->pluck('name', 'id')->toArray();
        $extras = $buy->extras;

        $types = Type::where('company_id', request()->company_id)->where('id', '!=', 5)->get();

        $stockEntries = StockEntry::where('buy_id', $buy->id)->get();

        $productSells = array();
        $productSellTotal = 0;
        foreach($stockEntries as $entry){
            if($entry->productStock){
                $sellStockEntry = SellStockEntry::where('stock_entry_id', $entry->id)->get();
    
                foreach($sellStockEntry as $out){
                    if($out->sell){
                        foreach($out->sell->products->where('pivot_deleted_at', null) as $product){
                            if($entry->id == $product->pivot->stock_entry_id){
                                array_push($productSells, $product);
                                $productSellTotal += ($product->pivot->price * $product->pivot->quantity);
                            }
                            // if($product->id == $entry->buyProduct->product_id){
                            //     array_push($productSells, $product);
                            //     $productSellTotal += ($product->pivot->price * $product->pivot->quantity);
                            // }   
                        }
                    }
                }
            }
        }

        $productSells = collect($productSells);
        $totalBuyProducts = 0;
        $totalPriceProducts = 0;
        foreach($buy->products->where('pivot.deleted_at', '==', null) as $product){
            $totalBuyProducts += $product->pivot->quantity;
            $totalPriceProducts += $product->pivot->price;
        }

        if($totalBuyProducts == $stockEntries->sum('amount'))
            $status['stock'] = 'Ok';
        else
            $status['stock'] = 'Pendente';

        if($totalPriceProducts + $buy->extras->sum('price') == $payments->sum('value'))
            $status['financing'] = 'Ok';
        else
            $status['financing'] = 'Pendente';

        $checks = Check::where('financial_output_id', null)->get();

        return view('dashboards.buy', compact('productSellTotal', 'productSells', 'attachments', 'allProducts', 'types', 'status', 'buy', 'payments', 'products', 'extras', 'stockEntries', 'checks'));
    }

    public function dashboardSell($companyId, $id)
    {
        $sell = Sell::where('id', $id)->first();
        $attachments = Attachment::where('fk_id', $id)->where('table_name', 'sells')->get();
        $receipts = $sell->financialInputs->where('is_deposit', 0);
        $extras = $sell->extras;
        $sell->extraReceiptsExpected = 0;
        $sell->extraReceipts = 0;

        foreach($extras as $extra){
            foreach($extra->financialOutput as $output){
                $sell->extraReceiptsExpected += $output->expected_value;
                $sell->extraReceipts += $output->value;
            }
        }

        $types = Type::where('company_id', request()->company_id)->where('id', '!=', 5)->get()->sortBy('name');
        $typesForInputModal = Type::where('company_id', request()->company_id)->where('id', '!=', 4)->where('id', '!=', 5)->get()->sortBy('name');

        $stockOuts = StockOut::where('sell_id', $sell->id)->get();

        $totalSellProducts = 0;
        $totalPriceProducts = 0;
        foreach($sell->products->where('pivot.deleted_at', '==', null) as $product){
            $totalSellProducts += $product->pivot->quantity;
            $totalPriceProducts += $product->pivot->price * $product->pivot->quantity;
        }

        if($totalSellProducts <= $stockOuts->sum('amount'))
            $status['stock'] = 'Ok';
        else
            $status['stock'] = 'Pendente';

        // if($totalPriceProducts - $sell->extras->sum('price') == $receipts->sum('value'))
        if($sell->discharge_date != null)
            $status['financing'] = 'Ok';
        else
            $status['financing'] = 'Pendente';

        if($sell->manifest)
            $status['manifest'] = 'Ok';
        else{
            $status['manifest'] = 'Pendente';
        }

        $buyTotal = 0;
        $buyDiscount = 0;
        $buyAddition = 0;
        $buyPrice = 0;
        $numberOfProducts = 0;
        $sellProductsCounter = 0;
        $buyExtras = [];
        $buyExtrasProfit = [];
        $cost = [];
        $costId = [];

        foreach($sell->products->where('pivot.deleted_at', null) as $product){
            if($product->pivot->stock_entry_id){
                $stockEntryId = $product->pivot->stock_entry_id;
                $stockEntry = StockEntry::find($stockEntryId);
                if($stockEntry && !$stockEntry->is_gain){
                    $buy = Buy::find($stockEntry->buy_id);

                    if($stockEntry->buyProduct){
                        if($stockEntry->buyProduct->quantity == 0)
                            $priceBuy = 0;
                        else
                            $priceBuy = $stockEntry->buyProduct->price / $stockEntry->buyProduct->quantity;

                        $totalBuy = 0;
                        foreach($buy->products->where('pivot.deleted_at', '==', null) as $p)
                            $totalBuy += $p->pivot->quantity;

                        foreach($buy->extras as $extra){
                            if($totalBuy != 0)
                                $extra->priceNew = $extra->price / $totalBuy * $product->pivot->quantity;
                            else
                                $extra->priceNew = $extra->price;

                            $buyExtras[] = $extra;
                            $buyExtrasProfit[] = $extra;
                        }

                        $extrasBuy = $buy->extras->sum('price');
                        array_push($cost, $priceBuy + ($totalBuy == 0 ? 0 : $extrasBuy / $totalBuy) - ($buy->discount == null || $buy->discount == 0 || $totalBuy == 0 ? 0 : $buy->discount / $totalBuy));
                    }
                    else{
                        $stockEntry->amount == 0 ? $priceBuy = 0 : $priceBuy = $stockEntry->total_price / $stockEntry->amount;
                        array_push($cost, $priceBuy);
                    }

                    $buyTotal += $priceBuy * $product->pivot->quantity;

                    array_push($costId, $buy->id);

                    foreach($buy->stockEntries as $buyStockEntry)
                        $numberOfProducts += $buyStockEntry->amount;

                    if($buy->products->sum('pivot.quantity') != 0 && $buy->discount != 0)
                        $discount = $buy->discount / $buy->products->sum('pivot.quantity') * $product->pivot->quantity;
                    else
                        $discount = $buy->discount;

                    if($buy->products->sum('pivot.quantity') != 0 && $buy->addition != 0)
                        $addition = $buy->addition / $buy->products->sum('pivot.quantity') * $product->pivot->quantity;
                    else
                        $addition = $buy->addition;

                    $buyDiscount += $discount;
                    $buyAddition += $addition;
                }
                else{
                    if($stockEntry && $stockEntry->is_gain){
                        if($stockEntry->productStock->product_id == $product->id){
                            $buyTotal += $stockEntry->total_price;
                            $numberOfProducts += $stockEntry->amount;
                            array_push($cost, $stockEntry->amount == 0 ? 0 : $stockEntry->total_price / $stockEntry->amount);

                            if($stockEntry->amount == 0)
                                array_push($costId, $stockEntry->id . '-0');
                            else
                                array_push($costId, $stockEntry->id . '-' . $stockEntry->total_price / $stockEntry->amount);
                        }
                    }
                }
            }
            $sellProductsCounter += $product->pivot->quantity;
        }

        // if($numberOfProducts == 0)
        //     $profit = $sell->total - (collect($buyExtrasProfit)->sum('price')) - collect($extras)->sum('price') - $buyTotal - $sell->discount + $sell->addition + $buyDiscount - $buyAddition;
        // else{
        //     $profit = $sell->total - (collect($buyExtrasProfit)->sum('price') / $numberOfProducts * $sellProductsCounter) - collect($extras)->sum('price') - $buyTotal - $sell->discount + $sell->addition + ($buyDiscount  / $numberOfProducts * $sellProductsCounter) - ($buyAddition  / $numberOfProducts * $sellProductsCounter);
        // }
        $profit = $sell->total - $sell->discount + $sell->addition - collect($buyExtrasProfit)->sum('priceNew') - $buyTotal + $buyDiscount - $buyAddition - $sell->extras->sum('price');
        $checks = Check::where('financial_output_id', null)->get();

        return view('dashboards.sell', compact('buyAddition', 'profit', 'costId', 'cost', 'buyDiscount', 'attachments', 'types', 'checks', 'status', 'sell', 'receipts', 'extras', 'stockOuts', 'buyTotal', 'buyExtras', 'typesForInputModal'));
    }

    public function dashboardSupplier($companyId, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $attachments = Attachment::where('fk_id', $id)->where('table_name', 'suppliers')->get();

        $products = Product::join('product_supplier', 'product_supplier.product_id', 'products.id')
            ->where('product_supplier.supplier_id', $id)
            ->select('products.*')->get();

        if(request()->first_day){
            $first_dates = explode('?', request()->first_day);
            $second_dates = explode('=', $first_dates[1]);
            $first_day = Carbon::createFromFormat('Y-m-d H', $first_dates[0] . 00);
            $second_day = Carbon::createFromFormat('Y-m-d H', $second_dates[1] . 24);
        }else{
            $first_day = Carbon::createFromFormat('Y-m-d H', Carbon::now()->year . '-01-01' . 00);
            $second_day = Carbon::tomorrow();
        }

        $sellProducts = $supplier->buys->where('created_at', '>=', $first_day)->where('created_at', '<=', $second_day);
        $totalSells = $sellProducts->sum('total') + $supplier->extras->sum('price') - $sellProducts->sum('discount');
        $totalSellProducts = 0;

        foreach($sellProducts as $buy)
            $totalSellProducts += count($buy->products->where('pivot.deleted_at', '==', null));

        $types = Type::where('company_id', request()->company_id)->where('id', '!=', 5)->get();
        $supplier->totalStockEntries = 0;
        $supplier->totalSacas = 0;
        $supplier->amountExtras = 0;
        $supplier->totalExtras = 0;

        foreach($sellProducts as $buy){
            $buy->stockEntries = StockEntry::where('buy_id', $buy->id)
              ->where('is_gain', false)
              ->orderBy('stock_entries.id', 'desc')
              ->get();

            $supplier->totalStockEntries += count($buy->stockEntries);
            $supplier->totalSacasEsperadas += $buy->stockEntries->sum('expected_amount');
            $supplier->totalSacas += $buy->stockEntries->sum('amount');
            $supplier->amountExtras += count($buy->extras);
            $supplier->totalExtras += $buy->extras->sum('price');
        }

        $wallets = Wallet::where('company_id', request()->company_id)->get();

        $providers = Provider::where('company_id', $companyId)->get();

        $receipts = $supplier->payments
            ->where('created_at', '>=', $first_day)->where('created_at', '<=', $second_day);

        $extras = $supplier->extras->where('created_at', '>=', $first_day)->where('created_at', '<=', $second_day);
        $receiptsValue = $receipts->sum('value');
        $debt = $totalSells - $receiptsValue;
        $checks = Check::where('financial_output_id', null)->where('value', '<=', $debt)->get();

        $merged = collect($receipts)->merge(collect($sellProducts))->merge($extras);
        $merged = $merged->sortBy('created_at');

        $saldo = array();
        $sumBalance = 0;
        foreach($merged as $key => $m){
            if($m->table == 'payments'){
                $sumBalance -= $m->value;
            }
            else{
                if($m->table == 'buys'){
                    $sumBalance += ($m->total - $m->discount - $m->advance + $m->addition);
                }
                else{
                    if($m->table == 'extras'){
                        $sumBalance -= $m->price;
                    }
                }
            }

            array_push($saldo, $sumBalance);
        }

        $balance = 0;
        $credit = $merged->sum('total') - $merged->sum('discount') - $merged->sum('advance') + $merged->sum('addition') + ($merged->where('price', '<', 0)->sum('price') * -1);
        $debit = $merged->sum('value') + $merged->where('price', '>', 0)->sum('price');

        if($debit < 0){
            $debit *= -1;
        }

        return view('dashboards.supplier', compact('saldo', 'second_day', 'first_day', 'debt', 'balance', 'debit', 'credit', 'merged', 'providers', 'attachments', 'wallets', 'totalSellProducts', 'types', 'supplier', 'products', 'sellProducts', 'totalSells', 'receipts', 'checks', 'debt'));
    }

    public function dashboardStock()
    {
        $stock = Stock::findOrFail(request()->stock_id);

        $attachments = Attachment::where('fk_id', request()->stock_id)->where('table_name', 'stocks')->get();

        if(request()->first_day){
            $first_dates = explode('?', request()->first_day);
            $second_dates = explode('=', $first_dates[1]);
            $first_day = Carbon::createFromFormat('Y-m-d H', $first_dates[0] . 00);
            $second_day = Carbon::createFromFormat('Y-m-d H', $second_dates[1] . 24);
        }else{
            $first_day = Carbon::createFromFormat('Y-m-d H', Carbon::now()->year . '-01-01' . 00);
            $second_day = Carbon::tomorrow();
        }

        $stockEntries = StockEntry::join('product_stock', 'product_stock.id', '=', 'stock_entries.product_stock_id')
            ->where('stock_entries.created_at', '>=', $first_day)
            ->where('stock_entries.created_at', '<=', $second_day)
            ->where('product_stock.stock_id', request()->stock_id)
            ->select('stock_entries.*')
            ->orderBy('stock_entries.id', 'desc')
            // ->limit(5)
            ->get();

        $stockOuts = StockOut::join('product_stock', 'product_stock.id', '=', 'stock_outs.product_stock_id')
            ->where('stock_outs.created_at', '>=', $first_day)
            ->where('stock_outs.created_at', '<=', $second_day)
            ->where('product_stock.stock_id', request()->stock_id)
            ->select('stock_outs.*')
            ->orderBy('stock_outs.id', 'desc')
            // ->limit(5)
            ->get();

        $productStock = DB::table('product_stock')
             ->join('products', 'products.id', 'product_stock.product_id')
             ->join('stock_entries', 'product_stock.id', 'stock_entries.product_stock_id')
             ->select(DB::raw('products.name, products.id, sum(stock_entries.remaining_quantity) as total'))
             ->where('stock_entries.remaining_quantity', '>', 0)
             ->where('stock_entries.deleted_at', null)
             ->where('stock_id', request()->stock_id)
             ->orderBy('products.name')
             ->groupBy('product_id')
             ->get();

        $stockTransfers = StockTransfer::where('created_at', '>=', $first_day)
            ->where('created_at', '<=', $second_day)
            ->limit(5)
            ->get();

        $gains = $stockEntries->where('is_gain', true)->take(5);
        $losses = $stockOuts->where('is_lost', true)->take(5);
        $stockEntries = $stockEntries->where('is_gain', false)->take(5);
        $stockOuts = $stockOuts->where('is_lost', false)->take(5);

        return view('dashboards.stock', compact('attachments', 'stockTransfers', 'productStock', 'gains', 'losses', 'stockEntries', 'stockOuts', 'stock', 'first_day', 'second_day'));
    }

    public function dashboardWallet($companyId, $id)
    {
        $wallet = Wallet::findOrFail(request()->wallet_id);

        $attachments = Attachment::where('fk_id', request()->wallet_id)->where('table_name', 'wallets')->get();

        $types = Type::where('company_id', request()->company_id)->where('id', '!=', 5)->get();

        if(request()->first_day){
            $first_dates = explode('?', request()->first_day);
            $second_dates = explode('=', $first_dates[1]);
            $first_day = Carbon::createFromFormat('Y-m-d H', $first_dates[0] . 00);
            $second_day = Carbon::createFromFormat('Y-m-d H', $second_dates[1] . 23);
        }else{
            $first_day = Carbon::createFromFormat('Y-m-d H', Carbon::now()->year . '-01-01' . 00);
            $second_day = Carbon::tomorrow();
        }

        $financialInputs = FinancialInput::join('type_wallet', 'type_wallet.id', '=', 'financial_inputs.type_wallet_id')
            ->where('financial_inputs.created_at', '>=', $first_day)
            ->where('financial_inputs.created_at', '<=', $second_day)
            ->where('type_wallet.wallet_id', $wallet->id)
            ->select('financial_inputs.*')
            ->orderBy('financial_inputs.id', 'desc')
            ->get();

        $financialOutputs = FinancialOutput::join('type_wallet', 'type_wallet.id', '=', 'financial_outputs.type_wallet_id')
            ->where('financial_outputs.created_at', '>=', $first_day)
            ->where('financial_outputs.created_at', '<=', $second_day)
            ->where('type_wallet.wallet_id', $wallet->id)
            ->select('financial_outputs.*')
            ->orderBy('financial_outputs.id', 'desc')
            ->get();

        $checksTable = Check::where('created_at', '>=', $first_day)
            ->where('created_at', '<=', $second_day)
            ->whereHas('financialInput', function( $query ) use($id){
                $query->where('company_id', request()->company_id )
                ->whereHas('typeWallet', function( $queryWallet ) use($id){
                    $queryWallet->where('wallet_id', $id );
                });
            })->orWhereHas('financialOutput', function( $query ) use($id){
                $query->where('company_id', request()->company_id )
                ->whereHas('typeWallet', function( $queryWallet ) use($id){
                    $queryWallet->where('wallet_id', $id );
                });
            })->get();

        $checksMerged = $checksTable->where('own', true);

        foreach($checksMerged as $c){
            $c->received_date = $c->expiration_date;
        }

        $payments = Payment::where('wallet_id', $wallet->id)->get();

        foreach($payments as $p){
            if($p->financialInputs->count() == 0 && $p->financialOutputs->count() == 0){
                $p->delete();
            }
        }
        $merged = Payment::where('wallet_id', $wallet->id)->get();

        $merged = collect($merged)->merge(collect($checksMerged));
        $merged = $merged->sortBy('received_date');
        $deposits = [];
        $withdraws = [];

        $saldo = array();
        $sumBalance = 0;
        foreach($merged as $key => $m){
            if($m->table == 'financial_inputs'){
                $sumBalance += $m->value;
            }
            else{
                if($m->table == 'payments'){
                    if($m->client_id != null){
                        $sumBalance += $m->value;
                    }
                    else{
                        if($m->financialOutputs->count() == 1){
                            if($m->financialOutputs[0]->checks->where('own', true)->count() == 1){
                                $merged->forget($key);
                                continue;
                            }
                            else{
                                $sumBalance -= $m->value;
                            }
                        }
                        else{
                            if($m->financialOutputs->count() > 1){
                                foreach($m->financialOutputs as $f){
                                    if($f->checks->where('own', true)->count() == 0){
                                        $sumBalance -= $f->value;
                                    }
                                    else{
                                        $m->value -= $f->checks->where('own', true)->sum('value');
                                    }
                                }

                                if($m->value <= 0){
                                    $merged->forget($key);
                                    continue;
                                }

                            }
                            elseif($m->financialInputs->count() > 0){
                                foreach($m->financialInputs as $f){
                                    $sumBalance += $f->value;
                                }
                            }
                        }
                    }
                }
                else{
                    $sumBalance -= $m->value;
                }
            }

            array_push($saldo, $sumBalance);
        }

        $j = 0;
        $i = 0;
        $totalMoney = 0;
        foreach($merged as $key => $merge){
            if($merge->received_date >= $first_day && $merge->received_date <= $second_day){
                $j++;
                $totalMoney = $saldo[$i];
            }
            $i++;
        }

        if($totalMoney != 0){
            $totalMoney -= $checksTable->where('own',false)->where('financial_output_id', null)->sum('value');
        }

        $typeWallets = null;

        $financialTransfers = FinancialTransfer::where('created_at', '>=', $first_day)
            ->where('created_at', '<=', $second_day)
            ->limit(5)
            ->get();

        $balanceWallet = TypeWallet::where('wallet_id', $id)->sum('balance');
        $checks = Check::where('own',false)
            ->where('created_at', '>=', $first_day)
            ->where('created_at', '<=', $second_day)
            ->where('financial_output_id', null)
            ->whereHas('financialInput', function( $query ) use($id){
                $query->where('company_id', request()->company_id )
                ->whereHas('typeWallet', function( $queryWallet ) use($id){
                    $queryWallet->where('wallet_id', $id );
                });
            })->get();

        $types = Type::where('company_id', request()->company_id)->where('id', '<=', 2)->get();

        return view('dashboards.wallet', compact('totalMoney', 'checksTable', 'saldo', 'checks', 'types', 'balanceWallet', 'attachments', 'merged', 'types', 'financialTransfers', 'typeWallets', 'deposits', 'withdraws', 'financialInputs', 'financialOutputs', 'wallet', 'first_day', 'second_day'));
    }

    public function dashboardProduct($companyId, $id)
    {
        $product = Product::findOrFail($id);
        $buys = [];
        $sells = [];

        if(request()->first_day){
            $first_dates = explode('?', request()->first_day);
            $second_dates = explode('=', $first_dates[1]);
            $first_day = Carbon::createFromFormat('Y-m-d H', $first_dates[0] . 00);
            $second_day = Carbon::createFromFormat('Y-m-d H', $second_dates[1] . 24);
        }else{
            $first_day = Carbon::createFromFormat('Y-m-d H', Carbon::now()->year . '-01-01' . 00);
            $second_day = Carbon::tomorrow();
        }

        $buyProducts = BuyProduct::where('product_id', request()->product_id)
            ->where('created_at', '>=', $first_day)
            ->where('created_at', '<=', $second_day)
            ->get();

        $productsSell = ProductSell::where('product_id', request()->product_id)
            ->where('created_at', '>=', $first_day)
            ->where('created_at', '<=', $second_day)
            ->get();

        if($buyProducts){
            foreach ($buyProducts as $buyProduct){
              $buys[] = Buy::find($buyProduct->buy_id);
            }
        }

        if($productsSell){
            foreach ($productsSell as $productSell){
              $sells[] = Sell::find($productSell->sell_id);
            }
        }

        return view('dashboards.product', compact( 'buys', 'sells', 'product', 'first_day', 'second_day'));
    }

    public function dashboardClient($companyId, $id)
    {
        $client = Client::findOrFail($id);
        $attachments = Attachment::where('fk_id', $id)->where('table_name', 'clients')->get();

        $types = Type::where('company_id', request()->company_id)->where('id', '!=', 5)->where('id', '!=', 4)->orderBy('name')->get();

        if(request()->first_day){
            $first_dates = explode('?', request()->first_day);
            $second_dates = explode('=', $first_dates[1]);
            $first_day = Carbon::createFromFormat('Y-m-d H', $first_dates[0] . 00);
            $second_day = Carbon::createFromFormat('Y-m-d H', $second_dates[1] . 24);
        }else{
            $first_day = Carbon::createFromFormat('Y-m-d H', Carbon::now()->year . '-01-01' . 00);
            $second_day = Carbon::tomorrow();
        }

        $buys = $client->sells->where('created_at', '>=', $first_day)
                                ->where('created_at', '<=', $second_day);
        $totalBuys = $buys->sum('total') + $client->extras->sum('price') - $buys->sum('discount') + $buys->sum('addition');

        $buyProducts = Sell::where('client_id', $id)
            ->join('product_sell', 'sells.id', '=', 'product_sell.sell_id')
            ->join('products', 'products.id', '=', 'product_sell.product_id')
            ->select('products.*', 'product_sell.quantity', 'product_sell.price', 'product_sell.sell_id')
            ->orderBy('sells.id', 'asc')
            ->get();

        $payments = $client->payments->where('created_at', '>=', $first_day)->where('created_at', '<=', $second_day);

        $extra = 0;
        $client->totalStockOuts = 0;
        $client->totalSacas = 0;
        $client->amountExtras = 0;
        $client->totalExtras = 0;

        foreach($buys as $sell){
            $extra += $sell->extras->sum('price');

            $sell->stockOuts = StockOut::where('sell_id', $sell->id)
                ->where('is_lost', false)
                ->orderBy('id', 'desc')
                ->get();

            $client->totalStockOuts += count($sell->stockOuts);
            $client->totalSacasEsperadas += $sell->stockOuts->sum('expected_amount');
            $client->totalSacas += $sell->stockOuts->sum('amount');
            $client->amountExtras += count($sell->extras);
            $client->totalExtras += $sell->extras->sum('price');
        }

        $checks = Check::where('financial_output_id', null)->get();
        $providers = Provider::where('company_id', $companyId)->get();
        $wallets = Wallet::where('company_id', request()->company_id)->get();
        $extras = $client->extras->where('created_at', '>=', $first_day)->where('created_at', '<=', $second_day);

        $merged = collect($payments)->merge(collect($buys))->merge($extras);
        $merged = $merged->sortBy('created_at');

        $saldo = array();
        $sumBalance = 0;
        foreach($merged as $key => $m){
            if($m->table == 'payments'){
                $sumBalance += $m->value;
            }
            else{
                if($m->table == 'sells'){
                    $sumBalance -= ($m->total - $m->discount + $m->addition - $m->advance);
                }
                else{
                    if($m->table == 'extras'){
                        $sumBalance -= $m->price;
                    }
                }
            }

            array_push($saldo, $sumBalance);
        }

        $balance = 0;
        $credit = $merged->sum('value') + ($merged->where('price', '<', 0)->sum('price') * -1);
        $debit = $merged->sum('total') + $merged->sum('addition') - $merged->sum('discount') - $merged->sum('advance') + ($merged->where('price', '>', 0)->sum('price'));

        return view('dashboards.client', compact('saldo', 'wallets', 'buys', 'second_day', 'first_day', 'balance', 'debit', 'credit', 'merged', 'providers', 'attachments', 'extra', 'types', 'client', 'buyProducts', 'totalBuys', 'payments', 'checks'));
    }

    public function dashboardProvider($companyId, $id)
    {
        $provider = Provider::findOrFail($id);

        $attachments = Attachment::where('fk_id', $id)->where('table_name', 'providers')->get();

        $types = Type::where('company_id', request()->company_id)->where('id', '!=', 5)->get();

        $wallets = Wallet::where('company_id', request()->company_id)->get();

        if(request()->first_day){
            $first_dates = explode('?', request()->first_day);
            $second_dates = explode('=', $first_dates[1]);
            $first_day = Carbon::createFromFormat('Y-m-d H', $first_dates[0] . 00);
            $second_day = Carbon::createFromFormat('Y-m-d H', $second_dates[1] . 24);
        }else{
            $first_day = Carbon::createFromFormat('Y-m-d H', Carbon::now()->year . '-01-01' . 00);
            $second_day = Carbon::tomorrow();
        }

        $extras = $provider->extras->where('created_at', '>=', $first_day)->where('created_at', '<=', $second_day);

        $totalPrice = $extras->sum('price');

        $buyExtras = [];
        $sellExtras = [];
        foreach($extras as $extra){
            if($extra->buy){
                if(!isset($buyExtras[$extra->buy->id])){
                    $buyExtras[$extra->buy->id] = $extra->buy->id;
                }
                $extra->created_at = $extra->buy->created_at;
            }

            if($extra->sell){
                if(!isset($sellExtras[$extra->sell->id])){
                    $sellExtras[$extra->sell->id] = $extra->sell->id;
                }
                $extra->created_at = $extra->sell->created_at;
            }
        }

        $financialOutputs = $provider->payments
        ->where('created_at', '>=', $first_day)->where('created_at', '<=', $second_day);

        $financialOutputTotal = $financialOutputs->sum('value');

        $debt = $totalPrice - $financialOutputTotal;

        $checks = Check::where('financial_output_id', null)->where('value', '<=', $debt)->get();

        $balanceTeste = 0;
        $balance = 0;
        $credit = $extras->where('operation', false)->sum('price') + ($extras->where('operation', true)->where('price', '<', 0)->sum('price') * -1);
        $debit = $financialOutputTotal + $extras->where('operation', true)->where('price', '>', 0)->sum('price');

        $merged = collect($financialOutputs)->merge(collect($extras));
        $merged = $merged->sortBy('created_at');

        $saldo = array();
        $sumBalance = 0;
        foreach($merged as $key => $m){
            if($m->table == 'payments'){
                $sumBalance -= $m->value;
            }
            else{
                if($m->table == 'extras'){
                    if($m->buy_id != null || $m->sell_id != null){
                        $sumBalance += $m->price;
                    }
                    else{
                        $sumBalance -= $m->price;
                    }
                }
            }

            array_push($saldo, $sumBalance);
        }

        return view('dashboards.provider', compact('saldo', 'merged', 'second_day', 'first_day', 'balanceTeste', 'debit', 'credit', 'balance', 'checks', 'debt', 'sellExtras', 'buyExtras', 'wallets', 'attachments', 'financialOutputs', 'types', 'provider', 'extras', 'totalPrice', 'financialOutputTotal'));
    }

    public function dashboardDay($companyId)
    {
        $types = Type::where('company_id', request()->company_id)->where('id', '!=', 5)->get();

        if(request()->first_day){
            $first_dates = explode('?', request()->first_day);
            $second_dates = explode('=', $first_dates[1]);
            $first_day = Carbon::createFromFormat('Y-m-d H', $first_dates[0] . 00);
            $second_day = Carbon::createFromFormat('Y-m-d H', $second_dates[1] . 24);
        }else{
            $first_day = Carbon::createFromFormat('Y-m-d H', Carbon::now()->year . '-01-01' . 00);
            $second_day = Carbon::tomorrow();
        }

        $financialInputs = FinancialInput::join('type_wallet', 'type_wallet.id', '=', 'financial_inputs.type_wallet_id')
            ->where('financial_inputs.created_at', '>=', $first_day)
            ->where('financial_inputs.created_at', '<=', $second_day)
            ->where('company_id', request()->company_id)
            ->select('financial_inputs.*')
            ->orderBy('financial_inputs.id', 'desc')
            // ->limit(5)
            ->get();

        $financialOutputs = FinancialOutput::join('type_wallet', 'type_wallet.id', '=', 'financial_outputs.type_wallet_id')
            ->where('financial_outputs.created_at', '>=', $first_day)
            ->where('financial_outputs.created_at', '<=', $second_day)
            ->where('company_id', request()->company_id)
            ->select('financial_outputs.*')
            ->orderBy('financial_outputs.id', 'desc')
            // ->limit(5)
            ->get();

        $merged = collect($financialInputs)->merge(collect($financialOutputs));
        $merged = $merged->sortBy('created_at');

        $providers = Provider::where('company_id', request()->company_id)->get();

        $extras = 0;
        foreach($providers as $provider){
            $extras += $provider->extras->sum('price');
        }

        $typeWallets = DB::table('type_wallet')
            ->join('types', 'types.id', 'type_wallet.type_id')
            ->select(DB::raw('types.name, sum(to_receive) as to_receive, sum(balance) as balance'))
            ->groupBy('type_wallet.type_id')
            ->get();

        // $typeWallets = DB::table('type_wallet')
        //     ->join('types', 'types.id', 'type_wallet.type_id')
        //     ->join('wallets', 'wallets.id', 'type_wallet.wallet_id')
        //     ->join('companies', 'companies.id', 'wallets.company_id')
        //     ->where('companies.id', request()->id)
        //     ->select(DB::raw('types.name, sum(to_receive) as to_receive, sum(balance) as balance'))
        //     ->groupBy('type_wallet.type_id')
        //     ->get();
            // dd($typeWallets);

        $financialTransfers = FinancialTransfer::where('created_at', '>=', $first_day)
            ->where('created_at', '<=', $second_day)
            // ->limit(5)
            ->get();

        $deposits = $financialInputs->where('is_deposit', true);
        $withdraws = $financialOutputs->where('is_withdraw', true);
        $financialInputs = $financialInputs->where('is_deposit', false);
        $financialOutputs = $financialOutputs->where('is_withdraw', false);

        return view('dashboards.day', compact('extras', 'merged', 'types', 'financialTransfers', 'typeWallets', 'deposits', 'withdraws', 'financialInputs', 'financialOutputs', 'first_day', 'second_day'));
    }

    public function dashboardCheck($companyId, $id)
    {
        $check = Check::findOrFail($id);

        // $attachments = Attachment::where('fk_id', $id)->where('table_name', 'providers')->get();

        // dd($check);
        $financialInput = $check->financialInput;
        $financialOutput = $check->financialOutput;

        return view('dashboards.check', compact('check', 'financialInput', 'financialOutput'));
    }

    public function dashboardPayment($companyId, $id)
    {
        $payment = Payment::find($id);

        if (empty($payment)) {
            Flash::error(\Lang::choice('tables.payments','s').' '.\Lang::choice('flash.not_found','m'));
            return redirect()->back();
        }

        $financial = $payment->financialInputs;
        $financial = $financial->merge($payment->financialOutputs);
        $financial = $financial->sortBy('received_date');

        return view('dashboards.payment', compact('payment', 'financial'));
    }

    public function manifest($id){
        if(request()->first_day){
            $first_dates = explode('?', request()->first_day);
            $second_dates = explode('=', $first_dates[1]);
            $first_day = Carbon::createFromFormat('Y-m-d H', $first_dates[0] . 00);
            $second_day = Carbon::createFromFormat('Y-m-d H', $second_dates[1] . 24);
        }else{
            $first_day = Carbon::createFromFormat('Y-m-d H', Carbon::now()->year . '-01-01' . 00);
            $second_day = Carbon::tomorrow();
        }

        $manifests = [];
        $sells = Sell::where('company_id', request()->company_id)
            ->where('manifest', true)
            ->where('created_at', '>=', $first_day)
            ->where('created_at', '<=', $second_day)
            ->orderByDesc('id')->get();

        $grossValueTotal = 0;
        $netProfitTotal = 0;
        foreach($sells as $sell){
            $netProfit = 0;

            if($sell->manifest){
                $buyTotal = 0;
                $buyPrice = 0;
                $extraPrice = 0;
                $numberOfProducts = 0;
                $sellProductsCounter = 0;
                $buyDiscount = 0;
                $buyAddition = 0;
                $buyExtras = [];
                $buyExtrasProfit = [];
                
                foreach($sell->products->where('pivot.deleted_at', null) as $product){
                    if($product->pivot->stock_entry_id){
                        $stockEntryId = $product->pivot->stock_entry_id;
                        $stockEntry = StockEntry::find($stockEntryId);
                        if($stockEntry && !$stockEntry->is_gain){
                            $buy = Buy::find($stockEntry->buy_id);
        
                            if($stockEntry->buyProduct){
                                if($stockEntry->buyProduct->quantity == 0)
                                    $priceBuy = 0;
                                else
                                    $priceBuy = $stockEntry->buyProduct->price / $stockEntry->buyProduct->quantity;
        
                                $totalBuy = 0;
                                foreach($buy->products->where('pivot.deleted_at', '==', null) as $p)
                                    $totalBuy += $p->pivot->quantity;
        
                                foreach($buy->extras as $extra){
                                    if($totalBuy != 0)
                                        $extra->priceNew = $extra->price / $totalBuy * $product->pivot->quantity;
                                    else
                                        $extra->priceNew = $extra->price;
        
                                    $buyExtras[] = $extra;
                                    $buyExtrasProfit[] = $extra;
                                }
        
                                $extrasBuy = $buy->extras->sum('price');
                            }
                            else{
                                $stockEntry->amount == 0 ? $priceBuy = 0 : $priceBuy = $stockEntry->total_price / $stockEntry->amount;
                            }
        
                            $buyTotal += $priceBuy * $product->pivot->quantity;
        
                            foreach($buy->stockEntries as $buyStockEntry)
                                $numberOfProducts += $buyStockEntry->amount;
        
                            if($buy->products->sum('pivot.quantity') != 0 && $buy->discount != 0)
                                $discount = $buy->discount / $buy->products->sum('pivot.quantity') * $product->pivot->quantity;
                            else
                                $discount = $buy->discount;
        
                            if($buy->products->sum('pivot.quantity') != 0 && $buy->addition != 0)
                                $addition = $buy->addition / $buy->products->sum('pivot.quantity') * $product->pivot->quantity;
                            else
                                $addition = $buy->addition;
        
                            $buyDiscount += $discount;
                            $buyAddition += $addition;
                        }
                        else{
                            if($stockEntry && $stockEntry->is_gain){
                                if($stockEntry->productStock->product_id == $product->id){
                                    $buyTotal += $stockEntry->total_price;
                                    $numberOfProducts += $stockEntry->amount;
                                }
                            }
                        }
                    }
                    $sellProductsCounter += $product->pivot->quantity;
                }

                $netProfit = $sell->total - $sell->discount + $sell->addition - collect($buyExtrasProfit)->sum('priceNew') - $buyTotal + $buyDiscount - $buyAddition - $sell->extras->sum('price');
                $grossValueTotal += $sell->total;
                $netProfitTotal += $netProfit;
                $manifests[] = [
                    "date" => $sell->readable_created_at,
                    "orderDate" => $sell->created_at,
                    "manifest" => $sell->id,
                    "client" => $sell->client->name,
                    "grossValue" => $sell->total,
                    "netProfit" => $netProfit,
                    "%" => $sell->total == 0 ? 0 : (($netProfit/$sell->total) * 100),
                    "clientId" => $sell->client->id,
                ];
            }
        }

        $company = Company::find(request()->company_id);
        return view('companies.manifestNote', compact('netProfitTotal', 'grossValueTotal', 'company', 'manifests', 'first_day', 'second_day'));
    }
}