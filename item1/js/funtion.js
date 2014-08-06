$(function(){
//得到2D渲染上下文
var cancans = document.getElementById("myCanvas");

var width = cancans.getAttribute("width");
var height = cancans.getAttribute("height");
var context = document.getElementById("myCanvas").getContext("2d");

var x = 40, y = 40;
context.fillStyle = "blue"; //设置填充色
context.fillRect(x, y, 55, 55);
  context.clearRect(x, y, 0, 0);

var timeoutlRst;
var resizeCavans = function () {
    //节流，防止频繁调用
    clearTimeout(timeoutlRst);
    setTimeout(SetCanvansSize, 500);
}
var SetCanvansSize = function () {
    var width = document.body.clientWidth;
    var height = $(window).get(0).innerHeight;//这里使用jquery的封装的方法得到高度
    cancans.width = width;
    cancans.height = height;
    context.fillStyle = "blue"; //设置填充色
    context.fillRect(0, 0, width, height);
}
window.onresize = resizeCavans;
})