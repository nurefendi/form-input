<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Test Tani yuk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Form Input</h5>
            <form method="POST" action="{{ route('submit-form') }}">
                @csrf
                <input type="hidden" id="dataID">
                <div class="mb-3">
                    <label class="form-label" for="berat_basah">Berat Basah:</label>
                    <input class="form-control" type="number" name="berat_basah" id="berat_basah" step="any" required min="0">
                </div>

                <div class="mb-3">
                    <label class="form-label" for="drc">DRC:</label>
                    <input class="form-control" type="number" name="drc" id="drc" step="any" required min="0" max="100">
                </div>

                <div class="mb-3">
                    <label class="form-label" for="keterangan">Keterangan:</label>
                    <textarea class="form-control" name="keterangan" id="keterangan" rows="4" cols="50"></textarea>
                </div>

                <button class="btn btn-primary" type="submit">Submit</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">List Data</h5>
            <table id="dataFormInput" class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Berat Basah</th>
                        <th>DRC</th>
                        <th>Berat Kering</th>
                        <th>Tanggal Dibuat</th>
                        <th>Tanggal Di Update</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        var isSaveData = true;
        document.addEventListener('DOMContentLoaded', function() {
            fetch('/api/forminputs')
                .then(response => response.json())
                .then(data => {
                    const tbody = document.querySelector('#dataFormInput tbody');

                    if (data.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="7">No data available.</td></tr>';
                        return;
                    }
                    let output = '';

                    data.forEach(item => {
                        const formattedCreatedAt = formatDate(new Date(item.created_at));
                        const formattedUpdatedAt = formatDate(new Date(item.updated_at));
                        output += `<tr>
                            <td>${item.id}</td>
                            <td>${item.berat_basah}</td>
                            <td>${item.drc}</td>
                            <td>${item.berat_kering}</td>
                            <td>${formattedCreatedAt}</td>
                            <td>${formattedUpdatedAt}</td>
                            <td>
                                <button class="btn btn-warning" onclick="editItem(${item.id})">Edit</button>
                                <button class="btn btn-danger" onclick="deleteItem(${item.id})">Delete</button>
                            </td>
                        </tr>`;
                    });

                    tbody.innerHTML = output;
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                    document.querySelector('#dataFormInput tbody').innerHTML = '<tr><td colspan="7">Error loading data.</td></tr>';
                });
        });

        function deleteItem(id) {
            if (!confirm('Are you sure you want to delete this item?')) {
                return;
            }
            isSaveData = false;
            fetch(`/api/forminputs/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message === 'Data deleted successfully') {
                        alert('Data deleted successfully');
                        location.reload();
                    } else {
                        alert('Error deleting data');
                    }
                })
                .catch(error => {
                    console.error('Error deleting data:', error);
                    alert('Error deleting data');
                });

                
        }

        function formatDate(date) {
            const options = {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };
            return date.toLocaleDateString('id-ID', options);
        }
        function editItem(id) {
            fetch(`/api/forminputs/${id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('dataID').value = data.id;
                    document.getElementById('berat_basah').value = data.berat_basah;
                    document.getElementById('drc').value = data.drc;
                    document.getElementById('keterangan').value = data.keterangan;
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        }
    </script>
</body>

</html>