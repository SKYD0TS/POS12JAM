<table id="model-datatable" border="1">
    <thead>
        <tr>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td></td>
        </tr>
    </tbody>
</table>

@push('scripts')
    {{-- ?VARIABLES --}}
    <script>
        const form = $('#model-form')
        let formColumns;
    </script>

    {{-- ?DATATABLES C0NFIG --}}
    <script>
        let table_id = 'model-datatable'
        let table
        $.ajax({
            url: "/api/" + '{{ $modeldata['header'] }}',
            type: "POST",
            success: function(response) {
                console.log(response)

                formColumns = response.formColumns

                response.columns.unshift(response.columns[0])

                response.columns[1] = ({
                    name: 'index',
                    title: '#',
                    orderData: 0,
                    defaultContent: "",
                })

                console.log(response.columns)
                /*EMPTY COLUMN FOR INDEX*/
                $.each(response.columns, function(i, v) {
                    $(`#${table_id} thead tr`).append("<th></th>")
                })

                table = $(`#${table_id}`).DataTable({
                    dom: "<lf<t>ip>",
                    processing: true,
                    serverSide: true,
                    paging: true,
                    length: 10,
                    pagingType: "full_numbers",
                    ajax: {
                        'type': 'POST',
                        'url': "/api/" + '{{ $modeldata['header'] }}',
                    },
                    columns: response.columns,
                    columnDefs: [{
                        targets: "_all",
                        defaultContent: "[-]"
                    }, {
                        targets: 1,
                        orderData: 0
                    }, {
                        targets: '_all',
                        render: function(data, type, row, meta) {
                            if (meta.settings.aoColumns[meta.col].column_type === 'list') {
                                const
                                    col = meta.settings.aoColumns[meta.col],
                                    name = col.name.substring(col.data.length + 1),
                                    propertyPath = name.split('.')

                                // return formatListData(data, name);
                                return formatNestedData(data, propertyPath)
                            }
                            return data; // Return an empty string for other types
                        }
                    }, ],
                    order: [
                        [0, "asc"]
                    ],

                    rowCallback: function(row, data, index) {
                        let dt = this.api()
                        let pageInfo = dt.page.info()
                        let newIndex = index + pageInfo.page * pageInfo.length
                        $("td:eq(0)", row).html(newIndex + 1)
                    },
                    initComplete: function(settings, json) {
                        // Attach the xhr event handler
                        var table = this;
                        $(document).ajaxSend(function(event, xhr, settings) {
                            console.log('DataTables AJAX request sent:', settings);
                        });
                        $(document).ajaxComplete(function(event, xhr, settings) {
                            console.log('DataTables AJAX response received:', xhr
                                .responseText);
                        });
                    }

                })
                // $.fn.dataTable.ext.errMode = "none"
            },
            error: function(er) {
                console.log(er)
            },
        })

        function formatNestedData(datas, propertyPath) {
            var listData = datas.map(function(data) {
                var nestedValue = propertyPath.reduce(function(obj, property) {
                    return obj[property];
                }, data);

                return nestedValue;
            });

            return listData.join("<br>");
        }
    </script>

    {{-- ?MODAL HANDLER --}}
    <script>
        let modalCaller;

        $(document).on('click', '.btn-add, .btn-edit', function(e) {
            modalCaller = $(e.target)
            console.log(modalCaller)
            $('#model-modal').modal('show')
        })


        $('#model-modal').on('show.bs.modal', function(e) {

            const modal = $(e.target),
                mode = modalCaller.data('modal_mode'),
                modalTitle = modal.find('.modal-title').text('Tambah data')

            if (mode == 'create') {
                form.attr('action', '{{ url()->full() }}')
                $('#form-method').text('')

            } else if (mode == 'edit') {
                const row = modalCaller.closest('tr'),
                    id = row.find('.id').text(),
                    modalTitle = modal.find('.modal-title').text('Edit data')

                $('#form-method').html('@method('PATCH')')
                form.find('.form-control').each(function(i) {
                    let d = row.find(`.${formColumns[i].name}`).text()
                    $(this).val(d)
                    console.log(formColumns[i].name, d)
                })
                form.attr('action', `{{ url()->full() }}/${id}`)

                modal.on('hide.bs.modal', function(e) {
                    cleanform()
                })
            }
            submitOnEnter()
        })
    </script>

    {{-- ?FORM SUBMIT HANDLER --}}
    <script>
        $('#model-modal').on('click', '.btn-form_submit', function(e) {
            $.ajax({
                url: form.attr('action'),
                method: form.attr('method'),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: form.serialize(),
                success: function(res) {
                    console.log(res, res.success)
                    if (typeof res.errors !== 'undefined') {
                        $.each(res.errors, function(key, value) {
                            let errorInput = $('[name=' + key + ']');
                            errorInput.addClass('is-invalid');
                            errorInput.siblings('.invalid-feedback').text(value);
                        });
                    } else {
                        table.ajax.reload()
                        toastr.options.timeOut = 3000
                        Command: toastr["success"](`${res.success}`)
                        $('#model-modal').modal('hide')

                        //?Clean input from form
                        cleanform()
                    }
                },
                error: function(er) {
                    console.log('Error : ', er.responseJSON)
                }
            })
        })

        $(`#${table_id}`).on('click', '.btn-delete', function(e) {
            const dname = '{{ $dname ?? ':eq(2)' }}'
            btn = $(this),
                row = btn.closest('tr'),
                id = row.find('.id').text(),
                name = row.find(`${dname}`).text(),
                deleteConfirmButton = '<button confirm-delete class="btn btn-sm btn-danger">Hapus</button>'

            toastr.options.timeOut = 5000
            Command: toastr["warning"](deleteConfirmButton,
                `Hapus Data <span class='badge bg-danger'>${name}</span>?`)


            $(document).on('click', 'button[confirm-delete]', function(e) {
                console.log('delete ' + name)
                $.ajax({
                    url: '/dashboard/' + '{{ $modeldata['header'] }}/' + id,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(s) {
                        table.ajax.reload()
                    },
                    error: function(e) {
                        console.log(e)
                    }
                })
            })

        })
    </script>

    {{-- ?UTILITIES --}}
    <script>
        // ?Clean form input
        function cleanform() {
            form.find('.form-control').each(function(i, k) {
                $(this).val('')
            })
        }

        // ?Remove errors on type
        $('#model-form :input').keydown(function() {
            $(this).removeClass('is-invalid');
            $(this).siblings('.invalid-feedback').text('');
        });

        // ?Self explanatory
        function submitOnEnter() {
            $(document).on('keydown', function(e) {
                if (e.keyCode === 13 && !e.shiftKey) {
                    // trigger click event on button
                    // console.log('submitted')
                    $('.btn-form_submit').click();
                }
            });
        }
    </script>
@endpush
