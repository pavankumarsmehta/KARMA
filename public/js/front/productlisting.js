var setFilterData = new Array();
var FilterData = new Array();
var flagcheckboxchecked = false;
var flagiemperpage = false;
$(function () {
  rebind_event();
  get_listing_Filters();
});

function rebind_event() {
  $(".filter_checkbox-js input.form-check-input-js")
    .off("click")
    .on("click", function () {
      var Page = 1;
      $(".list-more").attr("data-page", Page);
      get_product();
    });
  $(".list-more")
    .off("click")
    .on("click", function () {
      var Page = parseInt($(this).attr("data-page"));
      Page = Page + 1;
      $(this).attr("data-page", Page);
      get_product();
      if(!flagiemperpage){
        flagiemperpage = true;
        flagcheckboxchecked = true;  
      }
    });
  $("#sortopt")
    .off("change")
    .on("change", function () {
      if($(this).val() != ''){
        flagcheckboxchecked = true;  
      }else{
        if(!flagiemperpage){
          flagcheckboxchecked = false;  
        }
      }
      
      var Page = 1;
      $(".list-more").attr("data-page", Page);
      get_product();
    });
  $("#itemperpage")
    .off("change")
    .on("change", function () {
      flagiemperpage = true;
      flagcheckboxchecked = true;
      get_product();
    });
  $(".clear-all-filter-js")
    .off("click")
    .on("click", function () {
      $(".filter_checkbox-js").each(function (index, filterEle) {
        $(this)
          .find(".form-check")
          .each(function (actindex, actele) {
            if ($(this).find(".form-check-input-js").prop("checked")) {
              if (!$(this).find(".form-check-input-js").prop("disabled")) {
                $(this).find(".form-check-input-js").prop("checked", false);
              }
            }
          });
      });
      var Page = 1;
      $(".list-more").attr("data-page", Page);
      get_product();
    });
  $(".clear-filter-js")
    .off("click")
    .on("click", function () {
      var FilterType = $(this).attr("data-filter");
      var inputCheckboxId = $(this).attr("data-id");

      if ($("#" + FilterType + " #" + inputCheckboxId).prop("checked")) {
        if (!$("#" + FilterType + " #" + inputCheckboxId).prop("disabled")) {
          $("#" + FilterType + " #" + inputCheckboxId).prop("checked", false);
        }
      }
      var Page = 1;
      $(".list-more").attr("data-page", Page);
      get_product();
    });
}

function get_filter_by_id(FilterID) {
  var FilterData = [];
  if (FilterID == "categories") {
    if ($("#category_id").length > 0) {
      FilterData.push($("#category_id").val());
    }
  } else if (FilterID == "page") {
    var page = $(".list-more").attr("data-page");
    FilterData.push(page);
  } else if (FilterID == "itemperpage") {
    var itemperpage = $("#itemperpage").val();
    FilterData.push(itemperpage);
  }else if (FilterID == "sortby") {
    var itemperpage = $("#sortopt").val();
    FilterData.push(itemperpage);
  }else {
    $("#" + FilterID + " input.form-check-input").each(function (index, ele) {
      if ($(ele).is(":checked")) {
        // if (currentURL.indexOf('p4u/key-') > 0) {
        // 	FilterData.unshift($(ele).attr('data-id'));
        // }else{
        // 	FilterData.push($(ele).attr('data-id'));
        // }
        FilterData.push($(ele).attr("data-id"));
      }
    });
  }

  return FilterData;
}
function get_listing_Filters() {
  var currentURL = window.location.href;
  GetSelectedFilters();
  if (currentURL.indexOf("p4u/key-") > 0) {
    var SelProductTypes = get_filter_by_id("product_type");
    var SelBrands = get_filter_by_id("brand");
    var SelCategories = get_filter_by_id("category");
    var SelSize = get_filter_by_id("size");
    var SelGender = get_filter_by_id("gender");
    var SelPrice = get_filter_by_id("price");
    var Sortby = get_filter_by_id("sortby");
  } else {
    var SelProductTypes = get_filter_by_id("product_type");
    var SelCategories = get_filter_by_id("categories");
    var SelBrands = get_filter_by_id("brands");
    var SelSize = get_filter_by_id("size");
    var SelGender = get_filter_by_id("gender");
    var SelPrice = get_filter_by_id("price");
    var Sortby = get_filter_by_id("sortby");
  }
  var SortBy = $("#sortopt").val();
  var Filters = new Array();

  Filters = {
    categories: SelCategories,
    brands: SelBrands,
    size: SelSize,
    gender: SelGender,
    product_type: SelProductTypes,
    price: SelPrice,
    sortby: SortBy,
  };
  return JSON.stringify(Filters);
}

function get_product() {
  var Filters = get_listing_Filters();
  var token = $('meta[name="csrf-token"]').attr("content");
  $("#page-spinner").show();
  $("#product_listing").attr("data-load", "1");
  var productListingType = $("#product_listing_type").val();
  var itemperpage = $("#itemperpage").val();
  $.ajax({
    type: "POST",
    url: site_url + "/get_product",
    headers: {
      "X-CSRF-TOKEN": token,
    },
    data: {
      filters: Filters,
      itemperpage: itemperpage,
      product_listing_type: productListingType,
      page: parseInt($(".list-more").attr("data-page")),
    },
    success: function (response) {
      console.log(response);
      $("#page_limit").val(itemperpage);
      var pageLimit = parseInt($("#page_limit").val());
      var TotalProds = response.TotalProducts;
      $(".total-prouct-count-js").text(TotalProds + " Results");
      $(".list-more").attr("data-total", TotalProds);
      var PageSize = parseInt($(".list-more").attr("data-page")) * pageLimit;
      $("#noprod").hide();
      if (TotalProds > 0) {
        if (PageSize >= TotalProds) {
          $("#loadmore").hide();
        } else {
          $("#loadmore").show();
        }
      } else {
        $("#noprod").show();
        $("#loadmore").hide();
      }

      var page = parseInt($(".list-more").attr("data-page"));
      if (page > 1) {
        $("#product_listing").append(response.ProductHTML).fadeIn(1000);
      } else {
        $("#product_listing").html(response.ProductHTML).fadeIn(1000);
      }
      $("#page-spinner").hide();
      set_filter_link();
    },
    complete: function () {
      $("#product_listing").attr("data-load", "0");
    },
  });
}

function set_filter_link() {
  var flagfilterCheckeboxChecked = flagcheckboxchecked;
  var hashLink = "";

  if ($("#selcat").length > 0 && $("#selcat").attr("data-cat") != "")
    hashLink += "cid-" + $("#selcat").attr("data-cat").trim();

  addFilterLink("categories");
  addFilterLink("product_type");
  addFilterLink("brands");
  addFilterLink("gender");
  addFilterLink("size");
  addFilterLink("price");
  addFilterLink("page");
  addFilterLink("itemperpage");
  addFilterLink("sortby");

  function addFilterLink(filterName) {
   
    if (filterName == "page" || filterName == "itemperpage" || filterName == "sortby") {
      hashLink += get_link(filterName);
    } else {
      if (isCheckboxChecked(filterName)) {
        flagfilterCheckeboxChecked = true;
     
        hashLink += get_link(filterName);
      }
    }
  }

  function isCheckboxChecked(filterName) {
    var checkCount = $(
      "#" + filterName + " input.form-check-input:checked"
    ).length;
    if (checkCount > 0) {
      return true;
    }
  }
  if (hashLink != "") {

    if(flagfilterCheckeboxChecked){
      hashLink = "?" + hashLink.substring(1);
      window.location.hash = hashLink;
      history.pushState({}, null, hashLink);
    }else{
      window.history.replaceState(null, '', window.location.pathname);

    }
    return false;
  }
  return false;
}

function get_link(FilterID, currentHash) {
  var hashLink = currentHash || "";
  if (FilterID == "categories") {
    var CatLink = "";
    $("#categories li a").each(function (index, ele) {
      if ($(ele).hasClass("catactive")) CatLink += $(ele).attr("data-id") + ",";
    });
    if (CatLink != "") {
      hashLink = appendParamToURL(
        hashLink,
        "cid",
        CatLink.substr(0, CatLink.length - 1)
      );
    }
  } else {
    var Filter = get_filter_by_id(FilterID);
    var FilterKey = FilterID;
    if (FilterID == "product_type") FilterKey = "product_type";
    if (FilterID == "brands") FilterKey = "bid";
    if (FilterID == "gender") FilterKey = "gender";
    if (FilterID == "size") FilterKey = "size";
    if (FilterID == "price") FilterKey = "price";
    if (FilterID == "page") FilterKey = "page";
    if (FilterID == "itemperpage") FilterKey = "itemperpage";
    if (FilterID == "sortby") FilterKey = "sortby";

    if (Filter != "") {
      if (hashLink.indexOf(FilterKey + "=") > -1) {
        hashLink = updateParamInURL(hashLink, FilterKey, Filter.join(","));
      } else {
        hashLink = appendParamToURL(hashLink, FilterKey, Filter.join(","));
      }
    }
  }
  return hashLink;
}

function appendParamToURL(url, param, value) {
  console.log("new");
  var separator = url.indexOf("?") !== -1 ? "?" : "&";
  return url + separator + param + "=" + value;
}

function updateParamInURL(url, param, value) {
  console.log("update");
  return url.replace(new RegExp(param + "=([^&]*)"), param + "=$1," + value);
}

function GetSelectedFilters() {
  var FilterData = new Array();
  $(".filter_checkbox-js").each(function (index, filterEle) {
    var filterType = "";
    filterType = $(this).attr("data-filter-type");
    filterController = $(this).attr("data-filter-controller");
    filterTypeId = $(this).attr("id");
    if(filterController!='BrandController'){
    var li = "<li>" + filterType + ":";
    var checkboxCheckFlag = false;
    $(this)
      .find(".form-check")
      .each(function (actindex, actele) {
        if ($(this).find(".form-check-input-js").prop("checked")) {
          formCheckInputSel = $(this).find(".form-check-input-js");
          formCheckLabelSel = $(this).find(".form-check-label-js");
          var InputCheckboxId = formCheckInputSel.attr("id");

          if (!formCheckInputSel.prop("disabled")) {
            li +=
              '<a href="javascript:;" title="' +
              formCheckLabelSel.text() +
              '" class="clear-filter-js" data-filter="' +
              filterTypeId +
              '" data-id="' +
              InputCheckboxId +
              '">' +
              formCheckLabelSel.text();
            li +=
              '<svg class="svg_close" width="16px" height="16px" aria-hidden="true" role="img"><use href="#svg_close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_close"></use></svg>';
          } else {
            li +=
              '<a href="javascript:;" title="' +
              formCheckLabelSel.text() +
              '"  data-filter="' +
              filterTypeId +
              '" data-id="' +
              InputCheckboxId +
              '">' +
              formCheckLabelSel.text();
          }
          li += "</a>";
          checkboxCheckFlag = true;
        }
      });
    li += "</li>";
  }else{
    if(filterType !='Brands'){

      var li = "<li>" + filterType + ":";
    var checkboxCheckFlag = false;
    $(this)
      .find(".form-check")
      .each(function (actindex, actele) {
        if ($(this).find(".form-check-input-js").prop("checked")) {
          formCheckInputSel = $(this).find(".form-check-input-js");
          formCheckLabelSel = $(this).find(".form-check-label-js");
          var InputCheckboxId = formCheckInputSel.attr("id");

          if (!formCheckInputSel.prop("disabled")) {
            li +=
              '<a href="javascript:;" title="' +
              formCheckLabelSel.text() +
              '" class="clear-filter-js" data-filter="' +
              filterTypeId +
              '" data-id="' +
              InputCheckboxId +
              '">' +
              formCheckLabelSel.text();
            li +=
              '<svg class="svg_close" width="16px" height="16px" aria-hidden="true" role="img"><use href="#svg_close" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg_close"></use></svg>';
          } else {
            li +=
              '<a href="javascript:;" title="' +
              formCheckLabelSel.text() +
              '"  data-filter="' +
              filterTypeId +
              '" data-id="' +
              InputCheckboxId +
              '">' +
              formCheckLabelSel.text();
          }
          li += "</a>";
          checkboxCheckFlag = true;
        }
      });
    li += "</li>";

    }
  }
    if (checkboxCheckFlag) {
      FilterData.push(li);
    }
  });

  if (FilterData.length > 0) {
    FilterData.push(
      '<li><a href="javascript:;" title="Clear All" class="clear-all-filter-js">Clear All</a></li>'
    );
    var impData = FilterData.join(" ");
    $(".ft-list-js").html(impData);
    $(".ft-list-js").show();
    $(".ft-list-js li").find(".clear-filter-js").length;
    if ($(".ft-list-js li").find(".clear-filter-js").length <= 0) {
      $(".ft-list-js .clear-all-filter-js").hide();
    } else {
      $(".ft-list-js .clear-all-filter-js").show();
    }
  } else {
    $(".ft-list-js").html("");
    $(".ft-list-js").hide();
  }
  rebind_event();

  $("#product_listing .product_thumb img").on("mouseover", function () {
    var originalSrc = $(this).attr("src");
    var extraImageSrc = $(this).data("extra-image");
    if (extraImageSrc != "") {
      $(this).attr("src", extraImageSrc);
      $(this).data("extra-image", originalSrc);
    }
  });

  $("#product_listing .product_thumb img").on("mouseleave", function () {
    var originalSrc = $(this).attr("src");
    var extraImageSrc = $(this).data("extra-image");
    if (extraImageSrc != "") {
      $(this).attr("src", extraImageSrc);
      $(this).data("extra-image", originalSrc);
    }
  });
}
