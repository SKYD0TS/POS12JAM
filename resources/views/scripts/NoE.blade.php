@push('scripts')
    <script>
        let noeModel, currentNoE, prevNoeSearch = '-';
        $('.NewOrExist').on('show.bs.dropdown click focus', function(e) {
            currentNoE = $(e.target).closest('.NewOrExist')
            noeModel = currentNoE.find('.select-search').data('noeModel')
            if ($(e.target).is('button.btn-NewOrExist') && e.type == 'click') {
                /* !!! Ganti logika search pertama*/
                const li = currentNoE.find('.list-group.search-result-ul')
                let search = ''
                if (search != prevNoeSearch) {
                    noeSearch(search, li)
                }
            } else if ($(e.target).is('.search-result-item')) {
                currentNoE.find('input.input-noe').val($(e.target).data('id'))
                currentNoE.find('button.btn-NewOrExist .data').text($(e.target).text())
            }
        })

        $(document).ready(function() {
            let searchInput = $(".NewOrExist input.select-search"),
                searchDelayTimer

            searchInput.on("keyup", function(e) {
                let list = currentNoE.find('.list-group.search-result-ul')
                console.log($(e.target).val(), noeModel)
                clearTimeout(searchDelayTimer);
                // Set a new timer for a 500ms delay (adjust as needed)
                searchDelayTimer = setTimeout(function() {
                    // Perform an AJAX request to search the database
                    let search = $(e.target).val();
                    if (search != prevNoeSearch) {
                        noeSearch(search, list)
                    }
                }, 250); // Adjust the delay (in milliseconds) as needed
            });
        })

        function noeSearch(search, list) {
            prevNoeSearch = search;
            console.log('noeSearch')
            $.ajax({
                url: '{{ route('selectSearch', '') }}/' + noeModel,
                method: "GET",
                data: {
                    search: search
                },
                success: function(r) {
                    list.html('')
                    if (r.data == false) {
                        list.html(
                            `<li class="list-group-item text-danger">Tidak ada</li>`
                        )
                    } else {
                        r.data.forEach(function(i) {
                            i = Object.entries(i)
                            list.append(
                                `<button class="list-group-item list-group-item-action search-result-item" data-id="${i[0][1]}">${i[1][1]}</button>`
                            )
                        })
                    }
                },
                error: function(r) {
                    console.log(r)
                }
            });
        }
    </script>
@endpush
