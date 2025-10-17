<div id="view-orders" class="p-6" x-data="ordersManager()">
     <div class="mb-4"><input type="text" x-model.debounce.300ms="searchQuery" class="form-input" placeholder="Search by Order ID, Customer Name, Phone, Email, or Product Name..."></div>
     <template x-if="paginatedOrders.length === 0">
        <p class="text-gray-500 text-center py-10" x-text="searchQuery ? 'No orders match your search.' : 'No orders have been placed yet.'"></p>
     </template>
     <template x-if="paginatedOrders.length > 0">
        <div class="space-y-4">
            <template x-for="order in paginatedOrders" :key="order.order_id">
                <div class="bg-white border rounded-lg">
                    <div class="p-4 border-b flex justify-between items-center flex-wrap gap-4">
                        <div>
                            <h3 class="font-bold text-gray-800">Order #<span x-text="order.order_id"></span></h3>
                            <p class="text-sm text-gray-500" x-text="new Date(order.order_date).toLocaleString()"></p>
                        </div>
                        <div>
                            <span class="font-bold py-1 px-3 rounded-full text-sm" x-text="order.status" :class="{ 'bg-green-100 text-green-800': order.status === 'Confirmed', 'bg-red-100 text-red-800': order.status === 'Cancelled', 'bg-yellow-100 text-yellow-800': order.status === 'Pending' }"></span>
                        </div>
                    </div>
                    <div class="p-4 grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
                        <div>
                            <h4 class="font-semibold mb-2 text-gray-500 uppercase text-xs tracking-wider">Customer & Payment</h4>
                            <p><strong>Name:</strong> <span x-text="order.customer.name"></span></p>
                            <p><strong>Phone:</strong> <span x-text="order.customer.phone"></span></p>
                            <p><strong>Email:</strong> <span x-text="order.customer.email"></span></p>
                            <hr class="my-2">
                            <p><strong>Method:</strong> <span x-text="order.payment.method"></span></p>
                            <p><strong>TrxID:</strong> <span x-text="order.payment.trx_id"></span></p>
                        </div>
                        <div>
                            <h4 class="font-semibold mb-2 text-gray-500 uppercase text-xs tracking-wider">Items Ordered</h4>
                            <template x-for="item in order.items" :key="item.id + item.pricing.duration">
                                <div class="mb-1"><span x-text="item.quantity"></span>x <span x-text="item.name"></span> (<span x-text="item.pricing.duration"></span>)</div>
                            </template>
                        </div>
                        <div>
                            <h4 class="font-semibold mb-2 text-gray-500 uppercase text-xs tracking-wider">Summary & Actions</h4>
                            <p><strong>Subtotal:</strong> <span x-text="'৳' + order.totals.subtotal.toFixed(2)"></span></p>
                            <template x-if="order.totals.discount > 0">
                                <p class="text-green-600"><strong>Discount (<span x-text="order.coupon.code || 'N/A'"></span>):</strong> <span x-text="'-৳' + order.totals.discount.toFixed(2)"></span></p>
                            </template>
                            <p class="font-bold text-base mt-1"><strong>Total:</strong> <span x-text="'৳' + order.totals.total.toFixed(2)"></span></p>

                            <template x-if="order.status === 'Pending'">
                                <div class="mt-4 flex gap-2">
                                    <form action="api.php" method="POST"><input type="hidden" name="action" value="update_order_status"><input type="hidden" name="order_id" :value="order.order_id"><input type="hidden" name="new_status" value="Confirmed"><button type="submit" class="btn btn-success btn-sm">Confirm</button></form>
                                    <form action="api.php" method="POST"><input type="hidden" name="action" value="update_order_status"><input type="hidden" name="order_id" :value="order.order_id"><input type="hidden" name="new_status" value="Cancelled"><button type="submit" class="btn btn-danger btn-sm">Cancel</button></form>
                                </div>
                            </template>
                            <template x-if="order.status === 'Confirmed'">
                                <div class="mt-4 pt-4 border-t">
                                    <template x-if="!order.access_email_sent">
                                        <button @click="openModal(order.order_id, order.customer.email)" class="btn btn-primary btn-sm w-full">
                                            <i class="fa-solid fa-paper-plane"></i> Send Access Details
                                        </button>
                                    </template>
                                    <template x-if="order.access_email_sent">
                                        <div class="flex items-center justify-center gap-2 text-green-600 font-semibold bg-green-50 p-2 rounded-md text-sm">
                                            <i class="fa-solid fa-check-circle"></i>
                                            <span>Access Sent</span>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
        </div>
     </template>
     <div class="mt-6 flex justify-between items-center" x-show="totalPages > 1">
        <button @click="prevPage" :disabled="currentPage === 1" class="btn btn-secondary" :class="{'opacity-50 cursor-not-allowed': currentPage === 1}"><i class="fa-solid fa-chevron-left"></i> Previous</button>
        <span class="text-sm font-medium text-gray-700">Page <span x-text="currentPage"></span> of <span x-text="totalPages"></span></span>
        <button @click="nextPage" :disabled="currentPage === totalPages" class="btn btn-secondary" :class="{'opacity-50 cursor-not-allowed': currentPage === totalPages}">Next <i class="fa-solid fa-chevron-right"></i></button>
    </div>
</div>