import Image from "next/image";
import styles from "./page.module.css";

export default function Home() {
  return (
    <main className={styles.main}>
      <div id="page-spinner"></div>
      <div className={styles.modal && styles.fade} id="myModalPopUpLogin" tabIndex="-1" role="dialog" aria-labelledby="middle_popup1" aria-hidden="true"></div>
      <div className="modal fade tell-friend-popup" id="tell-friend-modal" role="dialog" aria-labelledby="middle_popup1" aria-hidden="true"></div>
      <div className="modal fade return-order-item" id="return-order-item-modal" role="dialog" aria-labelledby="middle_popup1" aria-hidden="true"></div>
      <div className="sb-slidebar sb-right sb-width-wide sb-style-overlay cart-right-slid" id="cart-open">
        <div className="sp-hd" tabIndex="0">
          Your Bag
          <a href="http://27.109.8.106:8253/81/hbalaravel/#close" rel="noindex nofollow" tabIndex="0" aria-label="Close" className="sb-close sb-cart-close">
            <svg className="svg_close vam" aria-hidden="true" role="img" width="25" height="25" loading="lazy">
              <use href="#svg_close" xmlnsXlink="http://www.w3.org/1999/xlink" xlinkHref="#svg_close"></use>
            </svg>
          </a>
        </div>
        <div className="sp-content sp-content-empty" id="shopcart">
          <div className="sp-inner sp-basket">
            <ol className="items empty-items">
              <li>
                <p tabIndex="0">Your Bag is Empty!</p>
                <p><a href="http://27.109.8.106:8253/81/hbalaravel/#continue_shopping" rel="noindex nofollow" tabIndex="0" aria-label="Continue Shopping" rel="nofollow" title="Continue Shopping" className="linksbb">Continue Shopping</a></p>
              </li>
            </ol>
          </div>
        </div>
      </div>
      <div className="sb-slidebar sb-left sb-width-custom sb-style-overlay" data-sb-width="320px">
        <div className="mm_slidebar">
          <div className="mm_top">Menu
            <svg className="svg_close sb-close" aria-hidden="true" role="img" width="24" height="24">
              <use href="#svg_close" xlinkHref="#svg_close"></use>
            </svg>
          </div>
          <div className="mm_mid">
            <div className="drilldown">
              <div className="drilldown-container">
                <ul className="drilldown-root">
                  <li className="drilldown-rarrow">
                    <a href="https://www.hbastore.com/skin-care.html" rel="nofollow" title="Skincare">Skincare</a>
                    <ul className="drilldown-sub">
                      <li className="drilldown-back">
                        <a href="https://www.hbastore.com/skin-care.html" rel="nofollow" title="Back">Back</a>
                      </li>
                      <li>
                        <a href="https://www.hbastore.com/skin-care.html" title="Skincare" style={{ textDecoration: "underline", fontSize: "20px" }}>Skincare</a>
                      </li>
                      <li className="drilldown-rarrow">
                        <div className="mm-acd">
                          <div className="mm-acd-loop act">
                            <input type="checkbox" name="mm-acd" id="mm-acd-1_6" />
                            <a href="http://27.109.8.106:8253/81/hbalaravel/#sub-category" title="Sub Category">Sub Category</a>
                            <label htmlFor="mm-acd-1_6" className="mm-acd-icon"></label>
                            <div className="mm-acd-con">
                              <a href="https://www.hbastore.com/skin-care/cleansers/cid/93" title="Cleansers" aria-label="Cleansers">Cleansers</a>
                              <a href="https://www.hbastore.com/skin-care/moisturizers/cid/60" title="Moisturizers" aria-label="Moisturizers">Moisturizers</a>
                              <a href="https://www.hbastore.com/skin-care/treatments/cid/96" title="Treatments" aria-label="Treatments">Treatments</a>
                              <a href="https://www.hbastore.com/skin-care/sun-care/cid/183" title="Sun Care" aria-label="Sun Care">Sun Care</a>
                              <a href="https://www.hbastore.com/skin-care/kits/cid/184" title="Kits" aria-label="Kits">Kits</a>
                            </div>
                          </div>
                          <div className="mm-acd-loop">
                            <input type="checkbox" name="mm-acd" id="mm-acd-1_27" />
                            <a href="http://27.109.8.106:8253/81/hbalaravel/#product-type" title="Product Type">Product Type</a>
                            <label htmlFor="mm-acd-1_27" className="mm-acd-icon"></label>
                            <div className="mm-acd-con">
                              <a href="https://www.hbastore.com/skin-care/face-wash/cid/185" title="Face Wash" aria-label="Face Wash">Face Wash</a>
                              <a href="https://www.hbastore.com/skin-care/exfoliaters/cid/186" title="Exfoliaters" aria-label="Exfoliaters">Exfoliaters</a>
                              <a href="https://www.hbastore.com/skin-care/makeup-removers/cid/82" title="Makeup Removers" aria-label="Makeup Removers">Makeup Removers</a>
                              <a href="https://www.hbastore.com/skin-care/day-creams/cid/187" title="Day Creams" aria-label="Day Creams">Day Creams</a>
                              <a href="https://www.hbastore.com/skin-care/night-creams/cid/188" title="Night Creams" aria-label="Night Creams">Night Creams</a>
                              <a href="https://www.hbastore.com/skin-care/hand-creams/cid/189" title="Hand Creams" aria-label="Hand Creams">Hand Creams</a>
                              <a href="https://www.hbastore.com/skin-care/face-oils/cid/190" title="Face Oils" aria-label="Face Oils">Face Oils</a>
                              <a href="https://www.hbastore.com/skin-care/seurms/cid/191" title="Seurms" aria-label="Seurms">Seurms</a>
                              <a href="https://www.hbastore.com/skin-care/masks/cid/94" title="Masks" aria-label="Masks">Masks</a>
                              <a href="https://www.hbastore.com/skin-care/sunscreen/cid/97" title="Sunscreen" aria-label="Sunscreen">Sunscreen</a>
                              <a href="https://www.hbastore.com/skin-care/self-tanners/cid/192" title="Self Tanners" aria-label="Self Tanners">Self Tanners</a>
                            </div>
                          </div>
                          <div className="mm-acd-loop">
                            <input type="checkbox" name="mm-acd" id="mm-acd-1_82" />
                            <a href="http://27.109.8.106:8253/81/hbalaravel/#shop-by-brand" title="Shop by Brand">Shop by Brand</a>
                            <label htmlFor="mm-acd-1_82" className="mm-acd-icon"></label>
                            <div className="mm-acd-con">
                              <a href="https://www.hbastore.com/brand/clarins/brid/31" title="Clarins" aria-label="Clarins">Clarins</a>
                              <a href="https://www.hbastore.com/brand/clinique/brid/32" title="Clinique" aria-label="Clinique">Clinique</a>
                              <a href="https://www.hbastore.com/brand/murad/brid/110" title="Murad" aria-label="Murad">Murad</a>
                              <a href="https://www.hbastore.com/brand/obagi/brid/121" title="Obagi" aria-label="Obagi">Obagi</a>
                              <a href="https://www.hbastore.com/brand/revision-skincare/brid/142" title="Revision Skincare" aria-label="Revision Skincare">Revision Skincare</a>
                            </div>
                          </div>
                          <div className="mm-acd-loop">
                            <input type="checkbox" name="mm-acd" id="mm-acd-1_83" />
                            <a href="http://27.109.8.106:8253/81/hbalaravel/#shop-by-price" title="Shop by Price">Shop by Price</a>
                            <label htmlFor="mm-acd-1_83" className="mm-acd-icon"></label>
                            <div className="mm-acd-con">
                              <a href="https://www.hbastore.com/skin-care/cid/32?price=1_25" title="Under $25" aria-label="Under $25">Under $25</a>
                              <a href="https://www.hbastore.com/skin-care/cid/32?price=25_50" title="$25 - $50" aria-label="$25 - $50">$25 - $50</a>
                              <a href="https://www.hbastore.com/skin-care/cid/32?price=50_100" title="$50 - $100" aria-label="$50 - $100">$50 - $100</a>
                              <a href="https://www.hbastore.com/skin-care/cid/32?price=100" title="Over $100" aria-label="Over $100">Over $100</a>
                            </div>
                          </div>
                        </div>
                      </li>
                    </ul>
                  </li>
                  <li className="drilldown-rarrow">
                    <a href="https://www.hbastore.com/skin-care.html" rel="nofollow" title="Skincare">Skincare</a>
                    <ul className="drilldown-sub">
                      <li className="drilldown-back">
                        <a href="https://www.hbastore.com/skin-care.html" rel="nofollow" title="Back">Back</a>
                      </li>
                      <li>
                        <a href="https://www.hbastore.com/skin-care.html" title="Skincare" style={{ textDecoration: "underline", fontSize: "20px" }}>Skincare</a>
                      </li>
                      <li className="drilldown-rarrow">
                        <div className="mm-acd">
                          <div className="mm-acd-loop act">
                            <input type="checkbox" name="mm-acd" id="mm-acd-1_227" />
                            <a href="http://27.109.8.106:8253/81/hbalaravel/#sub-category" title="Sub Category">Sub Category</a>
                            <label htmlFor="mm-acd-1_227" className="mm-acd-icon"></label>
                            <div className="mm-acd-con">
                              <a href="http://27.109.8.106:8253/81/hbalaravel/brand/biolage/brid/15" title="Wholesalemenu2" aria-label="Wholesalemenu2">Wholesalemenu2</a>
                            </div>
                          </div>
                        </div>
                      </li>
                    </ul>
                  </li>
                  <li className="drilldown-rarrow">
                    <a href="https://www.hbastore.com/hair-care.html" rel="nofollow" title="Haircare">Haircare</a>
                    <ul className="drilldown-sub">
                      <li className="drilldown-back">
                        <a href="https://www.hbastore.com/hair-care.html" rel="nofollow" title="Back">Back</a>
                      </li>
                      <li>
                        <a href="https://www.hbastore.com/hair-care.html" title="Haircare" style={{ textDecoration: "underline", fontSize: "20px" }}>Haircare</a>
                      </li>
                      <li className="drilldown-rarrow">
                        <div className="mm-acd">
                          <div className="mm-acd-loop act">
                            <input type="checkbox" name="mm-acd" id="mm-acd-1_21" />
                            <a href="http://27.109.8.106:8253/81/hbalaravel/#sub-category" title="Sub Category">Sub Category</a>
                            <label htmlFor="mm-acd-1_21" className="mm-acd-icon"></label>
                            <div className="mm-acd-con">
                              <a href="https://www.hbastore.com/hair-care/shampoos/cid/7" title="Shampoos" aria-label="Shampoos">Shampoos</a>
                              <a href="https://www.hbastore.com/hair-care/conditioners/cid/131" title="Conditioners" aria-label="Conditioners">Conditioners</a>
                              <a href="https://www.hbastore.com/hair-care/styling-products/cid/193" title="Styling Products" aria-label="Styling Products">Styling Products</a>
                              <a href="https://www.hbastore.com/hair-care/treatments/cid/45" title="Treatments" aria-label="Treatments">Treatments</a>
                              <a href="https://www.hbastore.com/hair-care/combos/cid/194" title="Combos" aria-label="Combos">Combos</a>
                            </div>
                          </div>
                          <div className="mm-acd-loop">
                            <input type="checkbox" name="mm-acd" id="mm-acd-1_35" />
                            <a href="http://27.109.8.106:8253/81/hbalaravel/#product-type" title="Product Type">Product Type</a>
                            <label htmlFor="mm-acd-1_35" className="mm-acd-icon"></label>
                            <div className="mm-acd-con">
                              <a href="https://www.hbastore.com/hair-care/clarifying/cid/195" title="Clarifying" aria-label="Clarifying">Clarifying</a>
                              <a href="https://www.hbastore.com/hair-care/moisturizing/cid/196" title="Moisturizing" aria-label="Moisturizing">Moisturizing</a>
                              <a href="https://www.hbastore.com/hair-care/volumizing/cid/197" title="Volumizing" aria-label="Volumizing">Volumizing</a>
                              <a href="https://www.hbastore.com/hair-care/deep-conditioners/cid/198" title="Deep Conditioners" aria-label="Deep Conditioners">Deep Conditioners</a>
                              <a href="https://www.hbastore.com/hair-care/leave-in/cid/199" title="Leave-In" aria-label="Leave-In">Leave-In</a>
                              <a href="https://www.hbastore.com/hair-care/rinse-out/cid/200" title="Rinse-Out" aria-label="Rinse-Out">Rinse-Out</a>
                              <a href="https://www.hbastore.com/hair-care/gets/cid/6" title="Gels" aria-label="Gels">Gels</a>
                              <a href="https://www.hbastore.com/hair-care/mousses/cid/73" title="Mousses" aria-label="Mousses">Mousses</a>
                              <a href="https://www.hbastore.com/hair-care/hairstylers/cid/201" title="Hairstylers" aria-label="Hairstylers">Hairstylers</a>
                              <a href="https://www.hbastore.com/hair-care/hair-masks/cid/202" title="Hair Masks" aria-label="Hair Masks">Hair Masks</a>
                              <a href="https://www.hbastore.com/hair-care/scalp-treatments/cid/203" title="Scalp Treatments" aria-label="Scalp Treatments">Scalp Treatments</a>
                              <a href="https://www.hbastore.com/hair-care/hair-oils/cid/204" title="Hair Oils" aria-label="Hair Oils">Hair Oils</a>
                            </div>
                          </div>
                          <div className="mm-acd-loop">
                            <input type="checkbox" name="mm-acd" id="mm-acd-1_93" />
                            <a href="http://27.109.8.106:8253/81/hbalaravel/#shop-by-brands" title="Shop by Brands">Shop by Brands</a>
                            <label htmlFor="mm-acd-1_93" className="mm-acd-icon"></label>
                            <div className="mm-acd-con">
                              <a href="https://www.hbastore.com/brand/biolage/brid/15" title="Biolage" aria-label="Biolage">Biolage</a>
                              <a href="https://www.hbastore.com/brand/chi/brid/30" title="CHI" aria-label="CHI">CHI</a>
                              <a href="https://www.hbastore.com/brand/dyson/brid/47" title="Dyson" aria-label="Dyson">Dyson</a>
                              <a href="https://www.hbastore.com/brand/ghd/brid/62" title="Ghd" aria-label="Ghd">Ghd</a>
                              <a href="https://www.hbastore.com/brand/k18/brid/86" title="K18" aria-label="K18">K18</a>
                            </div>
                          </div>
                          <div className="mm-acd-loop">
                            <input type="checkbox" name="mm-acd" id="mm-acd-1_94" />
                            <a href="http://27.109.8.106:8253/81/hbalaravel/#shop-by-price" title="Shop by Price">Shop by Price</a>
                            <label htmlFor="mm-acd-1_94" className="mm-acd-icon"></label>
                            <div className="mm-acd-con">
                              <a href="https://www.hbastore.com/hair-care/cid/3?price=1_15" title="Under $15" aria-label="Under $15">Under $15</a>
                              <a href="https://www.hbastore.com/hair-care/cid/3?price=15_30" title="$15 - $30" aria-label="$15 - $30">$15 - $30</a>
                              <a href="https://www.hbastore.com/hair-care/cid/3?price=30_50" title="$30 - $50" aria-label="$30 - $50">$30 - $50</a>
                              <a href="https://www.hbastore.com/hair-care/cid/3?price=50" title="Over $50" aria-label="Over $50">Over $50</a>
                            </div>
                          </div>
                        </div>
                      </li>
                    </ul>
                  </li>
                  <li className="drilldown-rarrow">
                    <a href="https://www.hbastore.com/eye-care.html" rel="nofollow" title="Eyecare">Eyecare</a>
                    <ul className="drilldown-sub">
                      <li className="drilldown-back">
                        <a href="https://www.hbastore.com/eye-care.html" rel="nofollow" title="Back">Back</a>
                      </li>
                      <li>
                        <a href="https://www.hbastore.com/eye-care.html" title="Eyecare" style={{ textDecoration: "underline", fontSize: "20px" }}>Eyecare</a>
                      </li>
                      <li className="drilldown-rarrow">
                        <div className="mm-acd">
                          <div className="mm-acd-loop act">
                            <input type="checkbox" name="mm-acd" id="mm-acd-1_47" />
                            <a href="http://27.109.8.106:8253/81/hbalaravel/#sub-category" title="Sub Category">Sub Category</a>
                            <label htmlFor="mm-acd-1_47" className="mm-acd-icon"></label>
                            <div className="mm-acd-con">
                              <a href="https://www.hbastore.com/eye-care/serum/cid/109" title="Serum" aria-label="Serum">Serum</a>
                              <a href="https://www.hbastore.com/eye-care/cream/cid/121" title="Cream" aria-label="Cream">Cream</a>
                              <a href="https://www.hbastore.com/eye-care/skin-treatment/cid/143" title="Skin Treatment" aria-label="Skin Treatment">Skin Treatment</a>
                              <a href="https://www.hbastore.com/eye-care/unisex/cid/39" title="Unisex" aria-label="Unisex">Unisex</a>
                            </div>
                          </div>
                          <div className="mm-acd-loop">
                            <input type="checkbox" name="mm-acd" id="mm-acd-1_48" />
                            <a href="http://27.109.8.106:8253/81/hbalaravel/#product-type" title="Product Type">Product Type</a>
                            <label htmlFor="mm-acd-1_48" className="mm-acd-icon"></label>
                            <div className="mm-acd-con">
                              <a href="https://www.hbastore.com/eye-care/unisex/cid/174" title="Balm" aria-label="Balm">Balm</a>
                              <a href="https://www.hbastore.com/eye-care/unisex/cid/38" title="Mascara" aria-label="Mascara">Mascara</a>
                              <a href="https://www.hbastore.com/eye-care/unisex/cid/163" title="Eyeshadow" aria-label="Eyeshadow">Eyeshadow</a>
                            </div>
                          </div>
                          <div className="mm-acd-loop">
                            <input type="checkbox" name="mm-acd" id="mm-acd-1_49" />
                            <a href="http://27.109.8.106:8253/81/hbalaravel/#shop-by-brand" title="Shop By Brand">Shop By Brand</a>
                            <label htmlFor="mm-acd-1_49" className="mm-acd-icon"></label>
                            <div className="mm-acd-con">
                              <a href="https://www.hbastore.com/eye-care/cream/cid/121?bid=52&page=1&itemperpage=48" title="Eminence" aria-label="Eminence">Eminence</a>
                              <a href="https://www.hbastore.com/eye-care/cream/cid/121?bid=79&page=1&itemperpage=48" title="Jan Marini" aria-label="Jan Marini">Jan Marini</a>
                              <a href="https://www.hbastore.com/eye-care/serum/cid/109?bid=131&page=1&itemperpage=48" title="Perricone" aria-label="Perricone">Perricone</a>
                              <a href="https://www.hbastore.com/eye-care/unisex/cid/39?bid=142&page=1&itemperpage=48" title="Revision Skincare" aria-label="Revision Skincare">Revision Skincare</a>
                              <a href="https://www.hbastore.com/eye-care/unisex/cid/39?bid=149&page=1&itemperpage=48" title="Sisley" aria-label="Sisley">Sisley</a>
                            </div>
                          </div>
                          <div className="mm-acd-loop">
                            <input type="checkbox" name="mm-acd" id="mm-acd-1_50" />
                            <a href="http://27.109.8.106:8253/81/hbalaravel/#shop-by-price" title="Shop By Price">Shop By Price</a>
                            <label htmlFor="mm-acd-1_50" className="mm-acd-icon"></label>
                            <div className="mm-acd-con">
                              <a href="https://www.hbastore.com/eye-care/cid/37?price=1_75" title="Under $75" aria-label="Under $75">Under $75</a>
                              <a href="https://www.hbastore.com/eye-care/cid/37?price=71_125" title="$75 - $125" aria-label="$75 - $125">$75 - $125</a>
                              <a href="https://www.hbastore.com/eye-care/cid/37?price=125_175" title="$125 - $175" aria-label="$125 - $175">$125 - $175</a>
                              <a href="https://www.hbastore.com/eye-care/cid/37?price=175_250" title="$175 - $250" aria-label="$175 - $250">$175 - $250</a>
                            </div>
                          </div>
                        </div>
                      </li>
                    </ul>
                  </li>

                </ul>
              </div>
            </div>
          </div>
          <div className="mm_contact">
            <a href="mailto:info@hbastore.com" rel="nofollow" aria-label="Email" title="Email">
              <svg className="svg-email" aria-hidden="true" role="img" width="28" height="28">
                <use xlinkHref="#svg-email"></use>
              </svg>
              <span>Email</span>
            </a>
            <a href="tel:1-000-000-0000" rel="nofollow" aria-label="Phone number" title="Phone number">
              <svg className="svg-phone" aria-hidden="true" role="img" width="28" height="28">
                <use xlinkHref="#svg-phone"></use>
              </svg>
              <span>Phone</span>
            </a>
            <a href="#" rel="nofollow" aria-label="Chat" title="Chat">
              <svg className="svg-chat" aria-hidden="true" role="img" width="28" height="28">
                <use xlinkHref="#svg-chat"></use>
              </svg>
              <span>Chat</span>
            </a>
          </div>
        </div>
        <div className="overlay"></div>
      </div>
      <div className="wrapper" id="sb-site">
        <div id="header-sticky-anchor">
        <div id="header-sticky"></div>
          <header>
            <section className="header_top">
              <div className="container">
                Get FREE shipping on all orders when you join HBAStore.{" "}
                <a href="http://27.109.8.106:8253/81/hbalaravel" title="More Detail">
                  <strong>More Detail</strong>
                </a>
              </div>
            </section>
            <div className="container">
              <section className="header_mid">
      <span
          className="sb-toggle-left hidden-lg-up"
          aria-label="Menu icon"
          title="Menu Icon"
          tabIndex="0"
      >
        <svg className="svg_menu" aria-hidden="true" role="img" width="25" height="27">
          <use href="#svg_menu" xmlnsXlink="http://www.w3.org/1999/xlink" xlinkHref="#svg_menu"></use>
        </svg>
      </span>
                <h1>
                  <a
                      href="http://27.109.8.106:8253/81/hbalaravel"
                      aria-label="HBA Store"
                      className="logo"
                      title="HBA Store"
                  >
                    <svg className="svg_logo" width="213px" height="55px" aria-hidden="true" role="img">
                      <use href="#svg_logo" xmlnsXlink="http://www.w3.org/1999/xlink" xlinkHref="#svg_logo"></use>
                    </svg>
                  </a>
                </h1>
                <div className="header_search hidden-md-down">
                  <form method="get" action="http://27.109.8.106:8253/81/hbalaravel/search" className="">
                    <div className="search_btn">
                      <svg className="svg_search" aria-hidden="true" role="img" width="23" height="23" fill="none">
                        <use href="#svg_search" xmlnsXlink="http://www.w3.org/1999/xlink" xlinkHref="#svg_search"></use>
                      </svg>
                    </div>
                    <input
                        type="text"
                        id="search-box"
                        name="query"
                        className="form-control input-search search-box"
                        placeholder="Search Products"
                        aria-label="Search Products"
                        aria-describedby="button-addon2"
                    />
                    <input type="hidden" name="dfParam_rpp" value="90" />
                  </form>
                </div>
                <ul className="header-link">
                  <li className="hidden-lg-up">
                    <a
                        href="http://27.109.8.106:8253/81/hbalaravel/#search"

                        rel="noindex nofollow"
                        id="mob_search"
                        aria-label="Search Icon"
                        title="Search"
                    >
                      <svg className="svg_search" aria-hidden="true" role="img" width="28" height="28" fill="none">
                        <use href="#svg_search" xmlnsXlink="http://www.w3.org/1999/xlink" xlinkHref="#svg_search"></use>
                      </svg>
                    </a>
                  </li>
                  <li>
                    <div className="userbox">
                      <a
                          href="http://27.109.8.106:8253/81/hbalaravel/#search"

                          rel="noindex nofollow"
                          className="hidden-lg-up"
                          aria-label="User Icon"
                          title="User"
                      >
                        <svg className="svg_user" aria-hidden="true" role="img" width="31" height="31">
                          <use href="#svg_user" xmlnsXlink="http://www.w3.org/1999/xlink" xlinkHref="#svg_user"></use>
                        </svg>
                      </a>
                      <div className="userbox_link hidden-md-down">
                        <svg className="svg_user" aria-hidden="true" role="img" width="35" height="35">
                          <use href="#svg_user" xmlnsXlink="http://www.w3.org/1999/xlink" xlinkHref="#svg_user"></use>
                        </svg>
                        <strong className="dblock">My Account</strong>
                        <span className="dblock">
                <a
                    href="http://27.109.8.106:8253/81/hbalaravel/#login"

                    rel="noindex nofollow"
                    tabIndex="0"
                    title="Sign In"
                    aria-label="Sign In"
                    className="me-2 loginpopup"
                >
                  Sign In
                </a>
                <a
                    href="http://27.109.8.106:8253/81/hbalaravel/#register"
                    rel="noindex nofollow"
                    tabIndex="0"
                    onClick="return show_user_register_popup();return false;"
                    title="Create Account"
                    aria-label="Create Account"
                >
                  Create Account
                </a>
              </span>
                      </div>
                      <div className="userbox-dropdown">
                        <div className="userbox-inner">
                          <div className="ubhead">
                            <div className="ubhead-text">
                              <div className="pe-2">
                                <span className="circle">Li</span>
                              </div>
                              <div>
                                <strong>Hi, Guest</strong> <span></span>
                              </div>
                            </div>
                            <div className="row row5">
                              <div className="col-xs-6">
                                <button
                                    type="button"
                                    onClick="window.location='http://27.109.8.106:8253/81/hbalaravel/login.html'"
                                    title="LOG IN"
                                    aria-label="LOG IN"
                                    className="btn btn-block"
                                >
                                  LOG IN
                                </button>
                              </div>
                              <div className="col-xs-6">
                                <button
                                    type="button"
                                    onClick="window.location='http://27.109.8.106:8253/81/hbalaravel/register.html'"
                                    title="SIGN UP"
                                    aria-label="SIGN UP"
                                    className="btn btn-border btn-block"
                                >
                                  SIGN UP
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a
                        href="http://27.109.8.106:8253/81/hbalaravel/#login"

                        rel="noindex nofollow"
                        tabIndex="0"
                        title="Wish List"
                        aria-label=""
                        className="me-2 loginpopup"
                    >
                      <span id="cart-item-count" className=" loginpopup"></span>
                      <svg className="svg_heart" aria-hidden="true" role="img" width="31" height="31">
                        <use href="#svg_heart" xmlnsXlink="http://www.w3.org/1999/xlink" xlinkHref="#svg_heart"></use>
                      </svg>
                    </a>
                  </li>
                  <li>
                    <a
                        href="http://27.109.8.106:8253/81/hbalaravel/#bag"
                        rel="noindex nofollow"
                        title="Bag"
                        className="sb-toggle-right sb-bag-js"
                        aria-label=""
                    >
                      <svg className="svg_cart" aria-hidden="true" role="img" width="31" height="31">
                        <use href="#svg_cart" xmlnsXlink="http://www.w3.org/1999/xlink" xlinkHref="#svg_cart"></use>
                      </svg>
                    </a>
                  </li>
                </ul>
                <div className="header_search_mob">
                  <div className="por">
                    <div className="header_search">
                      <form method="get" action="http://27.109.8.106:8253/81/hbalaravel/search" className="">
                        <div className="search_btn">
                          <svg className="svg_search" aria-hidden="true" role="img" width="23" height="23" fill="none">
                            <use href="#svg_search" xmlnsXlink="http://www.w3.org/1999/xlink" xlinkHref="#svg_search"></use>
                          </svg>
                        </div>
                        <input
                            type="text"
                            id="search-box-mo"
                            name="query"
                            className="form-control"
                            placeholder="Search Products"
                            aria-label="Search.."
                            aria-describedby="button-addon2"
                        />
                        <input type="hidden" name="dfParam_rpp" value="90" />
                      </form>
                    </div>
                  </div>
                </div>
              </section>
            </div>
          </header>
          <nav className="hidden-md-down">
            <div className="container">
              <ul className="menu" aria-label="Navigation bar">
                <li>
                  <a href="https://www.hbastore.com/skin-care.html" rel="nofollow" tabIndex="0" title="Skincare" aria-label="Skincare">Skincare</a>
                  <div className="menu-sub">
                    <div className="container">
                      <div className="menu-inner">
                        <div className="menu-col">
                          <h5><a href="Skin care sub3" tabIndex="0" title="Sub Category" aria-label="Sub Category">Sub Category</a></h5>
                          <ul className="mm-sub">
                            <li><a href="https://www.hbastore.com/skin-care/cleansers/cid/93" title="Cleansers" aria-label="Cleansers">Cleansers</a></li>
                            <li><a href="https://www.hbastore.com/skin-care/moisturizers/cid/60" title="Moisturizers" aria-label="Moisturizers">Moisturizers</a></li>
                            <li><a href="https://www.hbastore.com/skin-care/treatments/cid/96" title="Treatments" aria-label="Treatments">Treatments</a></li>
                            <li><a href="https://www.hbastore.com/skin-care/sun-care/cid/183" title="Sun Care" aria-label="Sun Care">Sun Care</a></li>
                            <li><a href="https://www.hbastore.com/skin-care/kits/cid/184" title="Kits" aria-label="Kits">Kits</a></li>
                          </ul>
                        </div>

                        <div className="menu-col">
                          <h5><a href="http://27.109.8.106:8253/81/hbalaravel#product-type" tabIndex="0" title="Product Type" aria-label="Product Type">Product Type</a></h5>
                          <ul className="mm-sub">
                            <li><a href="https://www.hbastore.com/skin-care/face-wash/cid/185" title="Face Wash" aria-label="Face Wash">Face Wash</a></li>
                            <li><a href="https://www.hbastore.com/skin-care/exfoliaters/cid/186" title="Exfoliaters" aria-label="Exfoliaters">Exfoliaters</a></li>
                            <li><a href="https://www.hbastore.com/skin-care/makeup-removers/cid/82" title="Makeup Removers" aria-label="Makeup Removers">Makeup Removers</a></li>
                            <li><a href="https://www.hbastore.com/skin-care/day-creams/cid/187" title="Day Creams" aria-label="Day Creams">Day Creams</a></li>
                            <li><a href="https://www.hbastore.com/skin-care/night-creams/cid/188" title="Night Creams" aria-label="Night Creams">Night Creams</a></li>
                            <li><a href="https://www.hbastore.com/skin-care/hand-creams/cid/189" title="Hand Creams" aria-label="Hand Creams">Hand Creams</a></li>
                            <li><a href="https://www.hbastore.com/skin-care/face-oils/cid/190" title="Face Oils" aria-label="Face Oils">Face Oils</a></li>
                            <li><a href="https://www.hbastore.com/skin-care/seurms/cid/191" title="Seurms" aria-label="Seurms">Seurms</a></li>
                            <li><a href="https://www.hbastore.com/skin-care/masks/cid/94" title="Masks" aria-label="Masks">Masks</a></li>
                            <li><a href="https://www.hbastore.com/skin-care/sunscreen/cid/97" title="Sunscreen" aria-label="Sunscreen">Sunscreen</a></li>
                            <li><a href="https://www.hbastore.com/skin-care/self-tanners/cid/192" title="Self Tanners" aria-label="Self Tanners">Self Tanners</a></li>
                          </ul>
                        </div>

                        <div className="menu-col">
                          <h5><a href="http://27.109.8.106:8253/81/hbalaravel/brand-skin-care.html" tabIndex="0" title="Shop by Brand" aria-label="Shop by Brand">Shop by Brand</a></h5>
                          <ul className="mm-sub">
                            <li><a href="https://www.hbastore.com/brand/clarins/brid/31" title="Clarins" aria-label="Clarins">Clarins</a></li>
                            <li><a href="https://www.hbastore.com/brand/clinique/brid/32" title="Clinique" aria-label="Clinique">Clinique</a></li>
                            <li><a href="https://www.hbastore.com/brand/murad/brid/110" title="Murad" aria-label="Murad">Murad</a></li>
                            <li><a href="https://www.hbastore.com/brand/obagi/brid/121" title="Obagi" aria-label="Obagi">Obagi</a></li>
                            <li><a href="https://www.hbastore.com/brand/revision-skincare/brid/142" title="Revision Skincare" aria-label="Revision Skincare">Revision Skincare</a></li>
                          </ul>
                        </div>

                        <div className="menu-col">
                          <h5><a href="http://27.109.8.106:8253/81/hbalaravel#shop-by-price" tabIndex="0" title="Shop by Price" aria-label="Shop by Price">Shop by Price</a></h5>
                          <ul className="mm-sub">
                            <li><a href="https://www.hbastore.com/skin-care/cid/32?price=1_25" title="Under $25" aria-label="Under $25">Under $25</a></li>
                            <li><a href="https://www.hbastore.com/skin-care/cid/32?price=25_50" title="$25 - $50" aria-label="$25 - $50">$25 - $50</a></li>
                            <li><a href="https://www.hbastore.com/skin-care/cid/32?price=50_100" title="$50 - $100" aria-label="$50 - $100">$50 - $100</a></li>
                            <li><a href="https://www.hbastore.com/skin-care/cid/32?price=100" title="Over $100" aria-label="Over $100">Over $100</a></li>
                          </ul>
                        </div>

                        <div className="menu-col-thumb mm-last-img">
                          <a href="https://www.hbastore.com/moisturizer/product/obagi-hydrate-facial-moisturizer-17oz/362032070193.html" tabIndex="0" title="Obagi Hydrate Facial Moisturizer 1.7oz" aria-label="Obagi Hydrate Facial Moisturizer 1.7oz">
                            <img src="http://27.109.8.106:8253/81/hbalaravel/public/images/spacer.gif" width="250" height="250" alt="Obagi Hydrate Facial Moisturizer 1.7oz" title="Obagi Hydrate Facial Moisturizer 1.7oz" data-src="MENU_IMG_2536.jpg"/>
                          </a>
                          <div className="menu_title" tabIndex="0">Obagi Hydrate Facial Moisturizer 1.7oz</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </li>

                <li>
                  <a href="https://www.hbastore.com/skin-care.html" rel="nofollow" tabIndex="0" title="Skincare" aria-label="Skincare">Skincare</a>
                  <div className="menu-sub">
                    <div className="container">
                      <div className="menu-inner">
                        <div className="menu-col">
                          <h5><a href="Skin care sub3" tabIndex="0" title="Sub Category" aria-label="Sub Category">Sub Category</a></h5>
                          <ul className="mm-sub">
                            <li><a href="http://27.109.8.106:8253/81/hbalaravel/brand/biolage/brid/15" title="Wholesalemenu2" aria-label="Wholesalemenu2">Wholesalemenu2</a></li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                </li>

                <li>
                  <a href="https://www.hbastore.com/hair-care.html" rel="nofollow" tabIndex="0" title="Haircare" aria-label="Haircare">Haircare</a>
                  <div className="menu-sub">
                    <div className="container">
                      <div className="menu-inner">
                        <div className="menu-col">
                          <h5><a href="http://27.109.8.106:8253/81/hbalaravel#sub-category" tabIndex="0" title="Sub Category" aria-label="Sub Category">Sub Category</a></h5>
                          <ul className="mm-sub">
                            <li><a href="https://www.hbastore.com/hair-care/shampoos/cid/7" title="Shampoos" aria-label="Shampoos">Shampoos</a></li>
                            <li><a href="https://www.hbastore.com/hair-care/conditioners/cid/131" title="Conditioners" aria-label="Conditioners">Conditioners</a></li>
                            <li><a href="https://www.hbastore.com/hair-care/styling-products/cid/193" title="Styling Products" aria-label="Styling Products">Styling Products</a></li>
                            <li><a href="https://www.hbastore.com/hair-care/treatments/cid/45" title="Treatments" aria-label="Treatments">Treatments</a></li>
                            <li><a href="https://www.hbastore.com/hair-care/combos/cid/194" title="Combos" aria-label="Combos">Combos</a></li>
                          </ul>
                        </div>

                        <div className="menu-col">
                          <h5><a href="http://27.109.8.106:8253/81/hbalaravel#product-type" tabIndex="0" title="Product Type" aria-label="Product Type">Product Type</a></h5>
                          <ul className="mm-sub">
                            <li><a href="https://www.hbastore.com/hair-care/clarifying/cid/195" title="Clarifying" aria-label="Clarifying">Clarifying</a></li>
                            <li><a href="https://www.hbastore.com/hair-care/moisturizing/cid/196" title="Moisturizing" aria-label="Moisturizing">Moisturizing</a></li>
                            <li><a href="https://www.hbastore.com/hair-care/volumizing/cid/197" title="Volumizing" aria-label="Volumizing">Volumizing</a></li>
                            <li><a href="https://www.hbastore.com/hair-care/deep-conditioners/cid/198" title="Deep Conditioners" aria-label="Deep Conditioners">Deep Conditioners</a></li>
                            <li><a href="https://www.hbastore.com/hair-care/leave-in/cid/199" title="Leave-In" aria-label="Leave-In">Leave-In</a></li>
                            <li><a href="https://www.hbastore.com/hair-care/rinse-out/cid/200" title="Rinse-Out" aria-label="Rinse-Out">Rinse-Out</a></li>
                            <li><a href="https://www.hbastore.com/hair-care/gets/cid/6" title="Gels" aria-label="Gels">Gels</a></li>
                            <li><a href="https://www.hbastore.com/hair-care/mousses/cid/73" title="Mousses" aria-label="Mousses">Mousses</a></li>
                            <li><a href="https://www.hbastore.com/hair-care/hairstylers/cid/201" title="Hairstylers" aria-label="Hairstylers">Hairstylers</a></li>
                            <li><a href="https://www.hbastore.com/hair-care/hair-masks/cid/202" title="Hair Masks" aria-label="Hair Masks">Hair Masks</a></li>
                            <li><a href="https://www.hbastore.com/hair-care/scalp-treatments/cid/203" title="Scalp Treatments" aria-label="Scalp Treatments">Scalp Treatments</a></li>
                            <li><a href="https://www.hbastore.com/hair-care/hair-oils/cid/204" title="Hair Oils" aria-label="Hair Oils">Hair Oils</a></li>
                          </ul>
                        </div>

                        <div className="menu-col">
                          <h5><a href="http://27.109.8.106:8253/81/hbalaravel/brand-hair-care.html" tabIndex="0" title="Shop by Brands" aria-label="Shop by Brands">Shop by Brands</a></h5>
                          <ul className="mm-sub">
                            <li><a href="https://www.hbastore.com/brand/biolage/brid/15" title="Biolage" aria-label="Biolage">Biolage</a></li>
                            <li><a href="https://www.hbastore.com/brand/chi/brid/30" title="CHI" aria-label="CHI">CHI</a></li>
                            <li><a href="https://www.hbastore.com/brand/dyson/brid/47" title="Dyson" aria-label="Dyson">Dyson</a></li>
                            <li><a href="https://www.hbastore.com/brand/ghd/brid/62" title="Ghd" aria-label="Ghd">Ghd</a></li>
                            <li><a href="https://www.hbastore.com/brand/k18/brid/86" title="K18" aria-label="K18">K18</a></li>
                          </ul>
                        </div>

                        <div className="menu-col">
                          <h5><a href="http://27.109.8.106:8253/81/hbalaravel#shop-by-price" tabIndex="0" title="Shop by Price" aria-label="Shop by Price">Shop by Price</a></h5>
                          <ul className="mm-sub">
                            <li><a href="https://www.hbastore.com/hair-care/cid/3?price=1_15" title="Under $15" aria-label="Under $15">Under $15</a></li>
                            <li><a href="https://www.hbastore.com/hair-care/cid/3?price=15_30" title="$15 - $30" aria-label="$15 - $30">$15 - $30</a></li>
                            <li><a href="https://www.hbastore.com/hair-care/cid/3?price=30_50" title="$30 - $50" aria-label="$30 - $50">$30 - $50</a></li>
                            <li><a href="https://www.hbastore.com/hair-care/cid/3?price=50" title="Over $50" aria-label="Over $50">Over $50</a></li>
                          </ul>
                        </div>

                        <div className="menu-col-thumb ">
                          <a href="https://www.hbastore.com/shampoo/product/american-crew-daily-moisturizing-shampoo338oz1-liter/669316068953.html" tabIndex="0" title="American Crew Daily Moisturizing Shampoo 33.8oz/1" aria-label="American Crew Daily Moisturizing Shampoo 33.8oz/1">
                            <img src="http://27.109.8.106:8253/81/hbalaravel/public/images/spacer.gif" width="250" height="250" alt="American Crew Daily Moisturizing Shampoo 33.8oz/1" title="American Crew Daily Moisturizing Shampoo 33.8oz/1" data-src="http://27.109.8.106:8253/81/hbalaravel/images/menuimage/MENU_IMG_1386.jpg?ver=1707113707"/>
                          </a>
                          <div className="menu_title" tabIndex="0">American Crew Daily Moisturizing Shampoo 33.8oz/1</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </li>
              </ul>
            </div>
          </nav>
        </div>
      </div>
    </main>
  );
}
















