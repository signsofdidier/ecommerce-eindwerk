<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
    <h1 class="text-4xl font-bold text-slate-500">My Orders</h1>
    <div class="flex flex-col bg-white p-5 rounded mt-4 shadow-lg">
        <div class="-m-1.5 overflow-x-auto">
            <div class="p-1.5 min-w-full inline-block align-middle">
                <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                        <tr>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Order</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Order Status</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Payment Status</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Order Amount</th>
                            <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($orders as $order)

                            @php
                                // kleuren voor de status van de order
                                switch ($order->status) {
                                    case 'new':
                                        $statusColor = 'bg-orange-500';
                                        break;
                                    case 'processing':
                                        $statusColor = 'bg-blue-500';
                                        break;
                                    case 'shipped':
                                        $statusColor = 'bg-green-500';
                                        break;
                                    case 'delivered':
                                        $statusColor = 'bg-green-500';
                                        break;
                                    case 'cancelled':
                                        $statusColor = 'bg-red-500';
                                        break;
                                }

                                // kleuren voor de status van de payment
                                switch ($order->payment_status){
                                    case 'pending':
                                        $paymentStatusColor = 'bg-yellow-500';
                                        break;
                                    case 'paid':
                                        $paymentStatusColor = 'bg-green-500';
                                        break;
                                    case 'failed':
                                        $paymentStatusColor = 'bg-red-500';
                                        break;
                                }


                            @endphp

                            <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-900 dark:even:bg-slate-800" wire:key="{{$order->id}}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">{{ $order->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ $order->created_at->format('d-m-Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                    <span class="{{ $statusColor }} py-1 px-3 rounded text-white shadow">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                    <span class="{{ $paymentStatusColor }} py-1 px-3 rounded text-white shadow">
                                        {{ $order->payment_status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ Number::currency($order->grand_total, 'EUR') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                    <a href="/my-orders/{{ $order->id }}" class="bg-slate-600 text-white py-2 px-4 rounded-md hover:bg-slate-500">View Details</a>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
            {{ $orders->links() }}
        </div>
    </div>
</div>
