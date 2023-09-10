<table class="w-100 table table-hover rounded-1" id="model-datatable" border="1">
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

<div id="delete-modal" class="modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h1 class="modal-title text-white fs-5" id="staticBackdropLabel">Modal title</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" confirm-delete>Hapus</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    {{-- ?VARIABLES --}}
    <script>
        const form = $('#model-form')
        let tblColumns;
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

                tblColumns = response.formColumns

                response.columns.unshift({
                    name: 'index',
                    searchable: false,
                    title: '#',
                    orderData: 1,
                    defaultContent: "",
                })

                console.log(response.columns)
                /*EMPTY COLUMN FOR INDEX*/
                $.each(response.columns, function(i, v) {
                    $(`#${table_id} thead tr`).append("<th></th>")
                })

                table = $(`#${table_id}`).DataTable({
                    dom: "<<'d-flex justify-content-between px-2 mb-2'fl><rt><ip>>",
                    oLanguage: {
                        "sSearch": "Cari :"
                    },
                    scrollX: true,
                    processing: true,
                    serverSide: true,
                    paging: true,
                    length: 10,
                    pagingType: "full_numbers",
                    ajax: {
                        'type': 'POST',
                        'url': "/api/" + '{{ $modeldata['header'] }}'
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
                            if (meta.settings.aoColumns[meta.col].column_type ===
                                'list') {
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
                        $('td.actions', row).html(
                            `<button class="btn btn-info btn-edit" data-modal_mode="edit">Edit</button>
                            <button class="btn btn-danger btn-delete">Hapus</button>`
                        )
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

            modal.focus();

            if (modalCaller.is('.btn-add')) {
                $('input#password').next('label').find('span').show()
                form.attr('action', '{{ url()->full() }}')
                $('#form-method').text('')

            } else if (modalCaller.is('.btn-edit')) {
                const row = modalCaller.closest('tr'),
                    id = row.find('.id').text(),
                    modalTitle = modal.find('.modal-title').text('Edit data')

                $('#form-method').html('@method('PATCH')')
                form.find('.form-control').each(function(i) {
                    console.log($(this))
                    let d = row.find(`.${tblColumns[i].name}`).text()
                    d == '[-]' ? d = '' : d = d //check null data

                    $(this).val(d)
                    console.log(tblColumns[i].name, d)
                })
                form.attr('action', `{{ url()->full() }}/${id}`)

                modal.on('hide.bs.modal', function(e) {

                    cleanform()
                })
                $('input#password').next('label').find('span').hide()
            }
        })
    </script>

    {{-- ?FORM SUBMIT HANDLER --}}
    <script>
        $('#model-modal').on('click', '.btn-form_submit', function(e) {
            const btn = $(e.target)
            btn.addClass('disabled')
            $.ajax({
                url: form.attr('action'),
                method: form.attr('method'),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: form.serialize(),
                success: function(res) {
                    console.log(res, res.success)
                    btn.removeClass('disabled')

                    if (typeof res.errors !== 'undefined') {
                        $.each(res.errors, function(key, message) {
                            let errorInput = $('[name=' + key + ']');
                            errorInput.addClass('is-invalid');
                            errorInput.siblings('.invalid-feedback').text(message);
                        });
                    } else {
                        table.ajax.reload()
                        toastr.options.timeOut = 3000
                        Command: toastr["success"](`${res.success}`)
                        btn.removeClass('disabled')
                        const firstField = form.find('.form-control').first(),
                            firstFieldName = firstField.attr('name')

                        // table.search(firstField.val()).draw()
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
        console.log()
        let delId;
        $(`#${table_id}`).on('click', '.btn-delete', function(e) {
            const dname = '{{ $dname ?? ':eq(2)' }}',
                btn = $(this),
                row = btn.closest('tr'),
                name = row.find(`${dname}`).text()

            delId = row.find('.id').text()
            $("#delete-modal").find('.modal-title').text('Hapus data?')
            $("#delete-modal").find('.modal-body').html('Hapus data <span class="badge bg-danger">' + name +
                '</span>?')
            $("#delete-modal").modal('show')
        })
        $(document).on('click', 'button[confirm-delete]', function(e) {
            $("#delete-modal").modal('hide')
            $.ajax({
                url: '/dashboard/' + '{{ $modeldata['header'] }}/' + delId,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(s) {
                    table.ajax.reload()
                    Command: toastr["success"](`${s.success}`)

                },
                error: function(e) {
                    // btn.removeClass('disabled')
                    console.log(e)
                    $("#delete-modal").modal('hide')
                }
            }).done()
        })
    </script>

    {{-- ?UTILITIES --}}
    <script>
        // ?Clean form input
        function cleanform() {
            $('#model-form :input').each((t) => {})
            form.find('.form-control').each(function(i, k) {
                $(this).val('')
                $(this).removeClass('is-invalid');
                $(this).siblings('.invalid-feedback').text('');
            })
        }

        // ?Remove errors on type
        $('#model-form :input').change(function() {
            $(this).removeClass('is-invalid');
            $(this).siblings('.invalid-feedback').text('');
        });
    </script>
@endpush
