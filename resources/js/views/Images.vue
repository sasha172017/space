<template>
    <div>
        <b-icon icon="three-dots" animation="cylon" class="mx-auto" font-scale="4"
                v-if="loading"></b-icon>
        <b-alert variant="danger" v-if="error" show>{{error}}
        </b-alert>
        <div class="d-flex drop-zone">
            <div style="margin-right:10px" v-for='(image,index) in items'
                 @drop='onDrop($event, index)'
                 @dragover.prevent
                 @dragenter.prevent
                 draggable
                 @dragstart='startDrag($event, index)'>
                <b-img thumbnail fluid v-bind="mainProps"
                       :src="'/storage/product/'+image.name"></b-img>
                <br><br>
                <div class="d-flex justify-content-center">
                    <b-button size="sm" @click="removeImage(index)" variant="danger">delete</b-button>
                </div>
            </div>
        </div>
        <br>
        <b-button size="sm" @click="saveSort()" v-if="saveSortButton" variant="primary">Save Sort</b-button>
    </div>
</template>

<script>
    import axios from "axios";

    export default {
        name: "Images",
        mounted() {
        },
        props: ['images'],
        watch: {
            images: function () {
                this.items = this.images;
            }
        },
        data() {
            return {
                saveSortButton: false,
                loading: false,
                error: false,
                items: null,
                mainProps: {width: 75, height: 75}
            }
        },
        methods: {
            sort() {
                this.items.sort((prev, next) => {
                    return prev.sort - next.sort
                });
            },
            saveSort(){
                this.loading = true;
                this.error = false;
                let params = [];
                for(let i = 0; i < this.items.length; i++){
                    params.push({id: this.items[i].id, sort: this.items[i].sort});
                }
                axios
                    .put('/api/image/product', {images: params}, {
                        headers: {
                            'Authorization': 'Bearer ' + this.$session.get('token')
                        }
                    })
                    .then(response => {
                        this.loading = false;
                        this.saveSortButton = false;
                    }).catch(error => {
                    this.loading = false;
                    this.error = error.response.data.message || error.message;
                });
            },
            startDrag: (evt, index) => {
                evt.dataTransfer.dropEffect = 'move';
                evt.dataTransfer.effectAllowed = 'move';
                evt.dataTransfer.setData('indexDrag', index);
            },
            removeImage(index) {
                let id = this.items[index].id;
                this.loading = true;
                this.error = false;
                axios
                    .delete('/api/image/' + id, {
                        headers: {
                            'Authorization': 'Bearer ' + this.$session.get('token')
                        }
                    })
                    .then(response => {
                        this.loading = false;
                        if (response.status == 204) {
                            this.items.splice(index, 1);
                        }
                    }).catch(error => {
                    this.loading = false;
                    this.error = error.response.data.message || error.message;
                });
            },
            onDrop(evt, indexDrop) {
                let indexDrag = evt.dataTransfer.getData('indexDrag');
                let itemDrag = this.items[indexDrag];
                let itemDrop = this.items[indexDrop];
                let dragSort = itemDrag.sort;
                this.items[indexDrag].sort = itemDrop.sort;
                this.items[indexDrop].sort = dragSort;
                this.sort();
                this.saveSortButton = true;
            }
        }
    }
</script>

<style scoped>

    .drag-el {
        background-color: #fff;
        margin-bottom: 10px;
        padding: 5px;
    }
</style>
