<head>


<style>
    .card-img-top {
  height: 400px; /* 设置固定高度 */
  object-fit: cover; /* 裁剪并覆盖容器 */
}
.soup-detail-image {
  width: 50%; /* 图像宽度为容器宽度 */
  height: 1000px; /* 设置固定高度与首页卡片视图一致 */
  object-fit: cover; /* 裁剪并覆盖容器 */
  object-position: center; /* 图像居中 */
}

</style>
</head>

<div class="soup-detail">
    <h1>{{ $soup->name }}</h1>
    <img src="{{ $soup->image_url }}" alt="{{ $soup->name }}" class="img-fluid soup-detail-image">
    <p>Ingredent: {{ $soup->description }}</p>
    <p>Method: {{ $soup->Cookingmethods }}</p>
    <p>Season: {{ $soup->season }}</p>
    <p>Benefits: {{ $soup->benefits }}</p>
    <p>Suitable_constitution: {{ $soup->suitable_constitution }}</p>
    <p>Unsuitable_for: {{ $soup->unsuitable_for }}</p>
    <!-- 這裡可以添加更多的湯水資料，比如適合的體質、不適合的人群等 -->
</div>


<script>
    var paragraphs = document.querySelectorAll('.soup-detail p');
    for (var i = 0; i < paragraphs.length; i++) {
        var paragraph = paragraphs[i];
        var words = paragraph.textContent.trim().split(' ');
        if (words.length > 0) {
            var firstWord = words[0];
            var updatedHTML = paragraph.innerHTML.replace(firstWord, '<span style="font-weight: bold; font-size: 1.5em;">' + firstWord + '</span>');
            paragraph.innerHTML = updatedHTML;
        }
    }
</script>
