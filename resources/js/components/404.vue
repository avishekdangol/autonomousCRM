<template>
    <div class="page">
        <div class="img-wrapper">
            <img :src="image" alt="">
            <h3 class="fw-bold text-uppercase">Page Not Found</h3>
        </div>
    </div>
</template>

<script>
export default ({
    data() {
        return {
            image: process.env.MIX_APP_URL + '/assets/404.gif',
        }
    },
})
</script>


<style scoped>
    .img-wrapper {
        text-align: center;
        color: #2E826D;
    }

    img {
        max-width: 100%;
        max-height: 100%;
    }

    h3 {
        position: relative;
        bottom: 48px;
    }

    .page {
        height: 100%;
        width: 100%;
        display: flex;
        flex-flow: column;
        justify-content: center;
    }
</style>
