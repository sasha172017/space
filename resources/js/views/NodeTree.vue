<template>
    <li class="node-tree">
        <router-link v-if="node.children == false" class="text-dark" :to="{ name: 'categoryShow', params: {id: node.id}}">{{ node.name }}</router-link>
        <span v-else>{{node.name}}</span>
        <ul v-if="node.children">
            <span v-for="child in node.children">
                <node-tree :node="child"></node-tree>
            </span>
        </ul>
    </li>
</template>

<script>
    export default {
        name: "node-tree",
        props: {
            node: Object
        }
    };
</script>
