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
        const modal = $('#model-modal')
        const deleteModal = $("#delete-modal")
    </script>

    {{-- ?DATATABLES C0NFIG --}}
    <script>
        let table = $(`#model-datatable`)

        const apiUrl = `/api/${encodeURIComponent('{{ $modeldata['header'] }}')}`;

        fetch(apiUrl, {
                method: 'POST',
            })
            .then((response) => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then((data) => {
                createDataTable(data)
            })
            .catch((error) => {
                console.error(error);
            });

        function formatNestedData(datas, propertyPath) {
            var listData = datas.map(function(data) {
                var nestedValue = propertyPath.reduce(function(obj, property) {
                    return obj[property];
                }, data);
                return nestedValue;
            });

            return listData.join("<br>");
        }

        function createDataTable(response) {
            const columns = response.columns;
            console.log(columns)
            // Add an empty column for index
            columns.unshift({
                name: 'index',
                searchable: false,
                title: '#',
                orderData: 1,
                defaultContent: "",
            });

            // Initialize the DataTable
            table = $(`#model-datatable`).DataTable({
                dom: "<<'d-flex justify-content-between px-2 mb-2'fl><rt><ip>>",
                oLanguage: {
                    "sSearch": "Cari :",
                },
                scrollX: true,
                processing: true,
                serverSide: true,
                paging: true,
                length: 10,
                pagingType: "full_numbers",
                ajax: {
                    'type': 'POST',
                    'url': apiUrl,
                },
                columns,
                columnDefs: [{
                        targets: "_all",
                        defaultContent: "[-]",
                    },
                    {
                        targets: 1,
                        orderData: 0,
                    },
                    {
                        targets: '_all',
                        render: function(data, type, row, meta) {
                            if (meta.settings.aoColumns[meta.col].column_type === 'list') {
                                const col = meta.settings.aoColumns[meta.col];
                                const name = col.name.substring(col.data.length + 1);
                                const propertyPath = name.split('.');
                                return formatNestedData(data, propertyPath);
                            }
                            return data; // Return an empty string for other types
                        },
                    },
                ],
                order: [
                    [0, "asc"],
                ],
                rowCallback: function(row, data, index) {
                    const dt = this.api();
                    const pageInfo = dt.page.info();
                    const newIndex = index + pageInfo.page * pageInfo.length;
                    $("td:eq(0)", row).html(newIndex + 1);
                    $('td.actions', row).html(
                        `<button class="btn btn-info btn-edit" data-modal_mode="edit">Edit</button>
         <button class="btn btn-danger btn-delete">Hapus</button>`
                    );
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
            });
        }
    </script>

    {{-- ?MODAL HANDLER --}}
    <script>
        //!might need more specifier on the document
        $(document).on('click', '.btn-add, .btn-edit', function(e) {
            const modalCaller = $(e.target)
            const mode = modalCaller.data('modal_mode')

            cleanform()
            modal.modal('show')
            modal.focus();

            if (modalCaller.is('.btn-add')) {
                modal.data('mode', 'add')
                modal.find('.modal-title').text('Tambah data')

                form.find('.form-method').text('')
                form.attr('action', '{{ url()->full() }}')
                form.find('input#password').siblings('label').find('span.require-symbol').show()

            } else if (modalCaller.is('.btn-edit')) {
                const row = modalCaller.closest('tr')
                const id = row.find('.id').text()

                modal.data('mode', 'edit')
                modal.find('.modal-title').text('Edit data')

                form.find('.form-method').html('@method('PATCH')')
                form.attr('action', `{{ url()->full() }}/${id}`)
                form.find('input#password').siblings('label').find('span.require-symbol').hide()

                //autofill input[edit-autofill]
                form.find('[edit-autofill]').each(function(i) {
                    const currentInput = $(this)
                    const iname = currentInput.attr('name') //get name of current input

                    let d = row.find(`.${iname}`).text() //get data from table row by the {dot}name
                    d == '[-]' || '' ? d = '' : d = d //check null data

                    currentInput.val(d) //insert data into input
                })
            }
        })
    </script>

    {{-- ?FORM SUBMIT HANDLER --}}
    <script>
        //? on submit
        modal.on('click', '.btn-form_submit', function(e) {
            const btn = $(e.target)
            form.data('submitting', true)
            btn.addClass('disabled')

            const formData = new FormData(form[0]);
            const data = Object.fromEntries(formData.entries());

            const url = form.attr('action')
            const options = {
                method: form.find('.form-method input').val() ?? form.attr('method'),
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                body: JSON.stringify(data),
            }

            fetch(url, options).then((response) => {
                if (!response.ok) {
                    console.error(response.text())
                } else {
                    return response.json()
                }
            }).then((res) => {
                form.data('submitting', false)
                btn.removeClass('disabled')
                //if there is input validation errors
                if (typeof res.errors !== 'undefined') {
                    $.each(res.errors, function(key, message) {
                        let errorInput = $('[name=' + key + ']');
                        errorInput.addClass('is-invalid');
                        errorInput.siblings('.invalid-feedback').text(message);
                    });
                    $(`[name=${Object.keys(res.errors)[0]}]`)[0].focus()

                } else {
                    //success
                    table.ajax.reload()
                    if (modal.data('mode') == 'add') {
                        table.order([0, 'desc']).draw()
                    }

                    toastr.options.timeOut = 3000
                    Command: toastr["success"](`${res.success}`)

                    modal.modal('hide')
                    cleanform()
                }
            }).catch((error) => {
                console.log('Error : ', error)
            })
        })

        table.on('click', '.btn-delete', function(e) {
            const btn = $(this)
            const row = btn.closest('tr')
            const name = row.find('.column-for-delete-name').first().text() || row.find(':eq(2)').first().text();

            deleteModal.data('deleteId', row.find('.id').text())
            deleteModal.find('.modal-title').text('Hapus data?')
            deleteModal.find('.modal-body').html(
                `Hapus data <span class="badge bg-danger">${name}</span>?`)
            deleteModal.modal('show')
        })

        $(document).on('click', 'button[confirm-delete]', function(e) {
            const deleteId = deleteModal.data('deleteId')

            $("#delete-modal").modal('hide')
            deleteData(deleteId)

        })

        // !!QUICK DELETE FEATURE

        function deleteData(id) {
            const url = `/dashboard/{{ $modeldata['header'] }}/${id}`;
            const options = {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            }
            fetch(url, options).then((response) => {
                    if (!response.ok) {
                        console.error(response.json())
                    } else {
                        return response.json(); // Parse the JSON response
                    }
                })
                .then((data) => {
                    table.ajax.reload();
                    toastr["success"](data.success);
                })
                .catch((error) => {
                    console.error(error);
                    $("#delete-modal").modal('hide');
                })
        }
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

        $('form').on('keydown', function(event) {
            if (event.keyCode === 13 && $(this).data('submitting')) {
                event.preventDefault();
                return false;
            }
        });
    </script>
@endpush
