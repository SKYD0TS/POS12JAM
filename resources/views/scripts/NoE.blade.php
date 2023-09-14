@push('scripts')
    <script>
        let noeModel, currentNoE, prevNoeSearch = '-';
        $('.NewOrExist').on('show.bs.dropdown click focus', function(e) {
            currentNoE = $(e.target).closest('.NewOrExist')
            noeModel = currentNoE.find('.btn-noe').data('noeModel')
            searchColumns = currentNoE.find('.btn-noe').data('searchColumns').split(',')
            console.log(currentNoE.find('.btn-noe'))

            if ($(e.target).is('button.btn-noe') && e.type == 'click' || e.type == 'show.bs.dropdown') {
                /* !!! Ganti logika search pertama*/
                const li = currentNoE.find('.list-group.search-result-ul')
                const search = ''
                if (search != prevNoeSearch) {
                    noeSearch(search, li)
                }
                // ?on item click?
            } else if ($(e.target).is('.search-result-item')) {
                currentNoE.find('input.input-noe').val($(e.target).data('id'))
                currentNoE.find('button.btn-noe .data').text($(e.target).text())
            }
        })

        $(document).ready(function() {
            let searchInput = $(".NewOrExist input.select-search"),
                searchDelayTimer

            searchInput.on("keyup", function(e) {
                let list = currentNoE.find('.list-group.search-result-ul')
                let search = $(e.target).val();

                clearTimeout(searchDelayTimer);
                console.log($(e.target).val(), noeModel)

                searchDelayTimer = setTimeout(function() {
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
                    search: search,
                    searchColumns: searchColumns
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
                            console.log(i)
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
