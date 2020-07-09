<template>
    <div>
        <router-link v-if="node.children.length < 1"  class="list-group-item list-group-item-action" :to="{ name: 'categoryShow', params: {id: node.id}}">{{ node.name }}</router-link>
        <span  class="list-group-item " v-else>{{node.name}}</span>
        <div v-if="node.children" style="padding-left: 1em">
            <br>
            <span v-for="child in node.children">
                <node-tree :node="child"></node-tree>
            </span>
        </div>
    </div>
</template>

<script>
    export default {
        name: "node-tree",
        props: {
            node: Object
        }
    };
</script>
