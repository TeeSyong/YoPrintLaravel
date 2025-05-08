<!DOCTYPE html>
<html>
<head>
    <title>CSV Upload</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        function uploadFile() {
            const fileInput = document.getElementById('fileInput');
            if (!fileInput.files[0]) {
                alert('Please select a file');
                return;
            }
            const formData = new FormData();
            formData.append('file', fileInput.files[0]);

            axios.post('{{ url('/upload') }}', formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            }).then(response => {
                console.log('Upload scheduled:', response.data);
                fetchStatus();
            }).catch(error => {
                console.error('Upload error:', error);
                alert('Upload failed');
            });
        }

        function fetchStatus() {
            axios.get('{{ url('/status') }}').then(response => {
                const statusList = document.getElementById('statusList');
                statusList.innerHTML = response.data.map(item => {
                    const createdAt = item.created_at
                        ? new Date(item.created_at).toLocaleString('en-US', {
                            dateStyle: 'medium',
                            timeStyle: 'short'
                          })
                        : 'N/A';
                    return `
                        <tr>
                            <td>${createdAt}</td>
                            <td>${item.file_name}</td>
                            <td class="status-${item.status}">${item.status}</td>
                        </tr>
                    `;
                }).join('');
            }).catch(error => {
                console.error('Status fetch error:', error);
            });
        }

        // Fetch status on page load
        document.addEventListener('DOMContentLoaded', fetchStatus);

        // Poll every 5 seconds
        setInterval(fetchStatus, 5000);
    </script>
</head>
<body>
    <h1>Upload CSV</h1>
    <div class="upload-section">
        <input type="file" id="fileInput" accept=".csv">
        <button onclick="uploadFile()">Upload File</button>
    </div>

    <table>
        <thead>
            <tr>
                <th>Time</th>
                <th>File Name</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody id="statusList"></tbody>
    </table>
</body>
</html>