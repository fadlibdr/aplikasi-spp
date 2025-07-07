    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('vendor/sb-admin-2/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/sb-admin-2/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('vendor/sb-admin-2/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('vendor/sb-admin-2/js/sb-admin-2.min.js') }}"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tables = document.querySelectorAll('table.table');
            tables.forEach(table => {
                const dt = $(table).DataTable();

                // Add a filter input for each column
                const header = table.tHead;
                if (!header) return;
                const filterRow = header.insertRow(-1);
                Array.from(header.rows[0].cells).forEach((th, idx) => {
                    const cell = filterRow.insertCell(idx);
                    const input = document.createElement('input');
                    input.type = 'text';
                    input.classList.add('form-control', 'form-control-sm');
                    input.placeholder = 'Filter';
                    input.addEventListener('keyup', () => {
                        dt.column(idx).search(input.value).draw();
                    });
                    cell.appendChild(input);
                });
            });
        });
    </script>
