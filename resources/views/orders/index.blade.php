<x-app-layout>
    @push('css')
        <!-- DataTables -->
        <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    @endpush

    <x-content-header title="Orders"></x-content-header>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <x-status-alert></x-status-alert>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Orders</h3>

                            <div class="card-tools">
                                @can('order.create')
                                    <a href="{{ route('orders.create') }}" class="btn btn-link" title="Add Order">
                                        <i class="fas fa-plus"></i>
                                        <span class="d-none d-lg-inline"> Add Order</span>
                                    </a>
                                @endcan
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table id="data-table" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Order No.</th>
                                        <th>Restaurant</th>
                                        <th>Customer</th>
                                        <th>Price (£)</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td>
                                                {{ $order->order_number }}
                                            </td>
                                            <td>
                                                {{ $order->restaurant->name }}
                                            </td>

                                            <td>
                                                {{ $order->user->name }}
                                            </td>
                                            <td>
                                                {{ number_format($order->net_total) }}
                                            </td>
                                            <td
                                                class="{{ $order->status == 'completed' ? 'text-success' : ($order->status == 'cancelled' ? 'text-danger' : 'text-warning') }}">
                                                {{ $order->status }}
                                            </td>
                                            <td>
                                                @can('order.show')
                                                    <a class="btn btn-secondary"
                                                        href="{{ route('orders.show', $order->id) }}" title="Show">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                @endcan

                                                @if ($order->user_id != Auth::user()->id)
                                                    <form action="{{ route('orders.update', $order->id) }}"
                                                        method="post" style="display: inline-block">
                                                        @csrf
                                                        @method('put')

                                                        @if ($order->status == 'pending')
                                                            <input type="hidden" name="status" value="completed">
                                                            <button class="btn btn-primary" title="Mark as Completed"
                                                                type="submit">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        @endif
                                                    </form>
                                                @else
                                                    <form action="{{ route('orders.update', $order->id) }}"
                                                        method="post" style="display: inline-block">
                                                        @csrf
                                                        @method('put')

                                                        @if ($order->status == 'pending')
                                                            <input type="hidden" name="status" value="cancelled">
                                                            <button class="btn btn-danger" title="Cancel Order"
                                                                type="submit">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        @endif
                                                    </form>
                                                @endif

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

    @push('js')
        <!-- DataTables  & Plugins -->
        <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
        <script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
        <script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
        <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

        <script>
            $(function() {
                $("#data-table").DataTable({
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                }).buttons().container().appendTo('#data-table_wrapper .col-md-6:eq(0)');
            });
        </script>
    @endpush
</x-app-layout>
