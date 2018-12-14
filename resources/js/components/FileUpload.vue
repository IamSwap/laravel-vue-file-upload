<template>
    <div class="container">
        <div class="row">
            <div class="col-sm-6 offset-sm-3 mt-5">
                <div class="card mb-5">
                    <div class="card-header">
                        File upload
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="uploadFile()" ref="uploadform">
                            <div class="form-group">
                                <label for="file">Select a file:</label>
                                <input type="file" name="document" class="d-block mt-2" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Upload file</button>
                        </form>
                    </div>
                </div>

                <div class="card mb-5">
                    <div class="card-header">
                        Uploaded files
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Download</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(file, index) in files" :key="file.id">
                                    <td>{{ file.id }}</td>
                                    <td>{{ file.name }}</td>
                                    <td>
                                    <a class="btn btn-sm btn-info" :href="`/download/${file.id}`">Download</a>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-danger" @click="deleteFile(file, index)">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import swal from 'sweetalert';

export default {
    data() {
        return {
            files: [],
        };
    },
    mounted() {
        this.fetchFiles();
    },
    methods: {
        fetchFiles() {
            axios.get('/api/files')
                .then(response => {
                    this.files = response.data;
                })
        },
        uploadFile() {
            let formData = new FormData(this.$refs.uploadform);

            axios.post('/api/files/', formData)
                .then(response => {
                    this.$refs.uploadform.reset();
                    swal('Success!', 'File uploaded successfully!', 'success');
                    this.files.push(response.data);
                }, error => {
                    swal('Oops!', error.response.data.message, 'error');
                });
        },
        deleteFile(file, index) {
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this imaginary file!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    axios.delete(`/api/files/${file.id}`)
                        .then(response => {
                            swal('Delete!', 'File deleted successfully!', 'success');
                            this.files.splice(index, 1);
                        }, error => {
                            swal('Oops!', error.response.data.message, 'error');
                        });
                } else {
                    swal("Your imaginary file is safe!");
                }
            });
        }
    }
}
</script>
