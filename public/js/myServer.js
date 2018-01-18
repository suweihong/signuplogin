

window.ser = {
  get(url, data){
    return $.get(url, data);
    // return $.get(`/${url}`, data);
  },
  post(url, data){
    return $.post(url, data);
    // return $.get(`/${url}`, data);
  },
  urlRes(url) {
    var obj = {};
    url.replace(/[&\?]{1}([\w]+)=([\w]+)/g, (match, p1, p2) => {
      obj[p1] = p2;
      return "";
    });

    return obj;
  },
  showData(data, time = 2000, node = $("#my-error")) {
    node.children("p").html(data);
    node.show();
    setTimeout(() => {
      node.hide();
    }, time);
    node.children("div").click(e => node.hide());
  },
};

$(".return i").click(e => {
  console.log(e);
  console.log("触发后退！！！")
  window.history.back();
});

` 页面跳转：
window.location.href='hello.html';
`;
