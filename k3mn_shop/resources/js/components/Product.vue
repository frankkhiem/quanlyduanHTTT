<template>
    <div>
        <div class="header">
          <div class="container">
            <div class="navbar">
              <div class="logo">
                <a href="/home"><img src="../images/Logo.png" width="150px"></a>
              </div>
              <nav>
                <ul>
                  <li><router-link to="/home">Trang chủ</router-link></li>
                  <li><router-link to="/products">Sản phẩm</router-link></li>
                  <li v-if="set_user == false"><a href="/login">Đăng nhập</a></li>
                  <li>
                    <form @submit="search($event)">
                      <input type="text" v-model="keyword" placeholder="Tìm kiếm sản phẩm..." />
                      <button type="submit"><i class="fas fa-search"></i>>></button>
                    </form>
                  </li>
                </ul>
              </nav>
              <router-link to="/listorders" v-if="set_user == true"><img src="../images/Cart.png" width="30px" height="30px"></router-link>
              <div class="account">
                  <img v-if="set_user == true && user[0].avatar == null" class="avatar" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS1CfdSF5Sdj53VRzQtJe8dgcoDLSyH5tK_sGgyhlfs91uiPe4FAg0u_nsBPDIGovorvso&usqp=CAU" width="30px" height="30px" @click="show_acc = !show_acc">
                        <img v-if="set_user == true && user[0].avatar != null" class="avatar" :src="'/uploads/avatar/' + user[0].avatar" width="30px" height="30px" @click="show_acc = !show_acc">
                  <div class="dropdown-menu" v-show="show_acc == true">
                      <p v-if="user[0] != null"><b>{{ user[0].name }}</b></p>
                      <hr>
                      <router-link to="/profile">Hồ sơ</router-link>
                      <a href="#" @click="logout($event)">Đăng xuất</a>
                  </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Phan thong tin san pham -->
        
        <div class="small-container product-details-page" v-if="product[0] != null">
            <h1>Thông tin sản phẩm</h1>
            <div class="row">
                <div class="col2">
                    <img v-if="changeAvt == false" :src="'../uploads/imagesProduct/' + product[0].thumbnail" width="100%" id="ProductImg">
                    <img v-else :src="Avt_src" width="100%" height="500px" id="ProductImg">
				    <div class="small-img-row">
                         <div class="small-img-col" v-for="(image, index) in images" :key="index">
                            <img :src="'../uploads/imagesProduct/' + image" width="100%" class="small-img" @click="gotoAvatar(image)">
                        </div>
				    </div>
                
                </div>
                <div class="col2">
                    <h2>{{ product[0].name }}</h2>
                    <p><b>{{ product[0].price | FomatPrice }}</b></p>
                    <h3>Tình trạng : {{ product[0].status_product.name }}</h3>
                    <button v-if="product[0].status_product.name == 'Còn hàng'" class="btn" @click="buy($event, product[0].id)">Mua ngay</button>
                    <button v-else class="btn" @click="buy($event, product[0].id)">Đặt trước</button>
			    </div>
            </div>
        </div>
        <!--Mo ta, danh gia, binh luan-->
	<div class="small-container">
		<h2>Thông số kỹ thuật</h2>
		<table>
			<tr>
				<th>Thuộc tính</th>
				<th>Mô tả</th>
			</tr>
			<tr v-for="(infor, index) in product[3]" :key="index">
				<td>{{ infor.attribute }}</td>
				<td>{{ infor.information }}</td>
			</tr>
		</table>
		<h2>Bình luận</h2>
		<div class="comment" v-for="(comment, index) in comments" :key="index">
			<h4 class="you" v-if="user[0] != null && comment.user_id == user[0].id"><strong>{{ comment.user_name }}</strong></h4>
            <h4 v-else><strong>{{ comment.user_name }}</strong></h4>
			<p>{{ comment.content }}</p>
        </div>
        <div>
            <button class="seeMore" v-if="cmt_current_page < cmt_last_page" @click="moreComments()">Xem thêm ></button>
        </div>
        <div class="input-comment">
            <textarea name="comments" cols="50" rows="5" v-model="new_comment.content" placeholder="nhập bình luận của bạn tại đây..."></textarea>
            <br>
            <button class="btn" @click="comment($event, new_comment)">Gửi</button>
        </div>
	</div>

	<!--title-->
	<div class="small-container" v-if="recommend_products[0] != null">
            <h1>Sản phẩm liên quan</h1>
			<div class="row">
				<div class="col-4" v-for="(recommend_product, index) in recommend_products" :key="index" @click="goToProduct(recommend_product.id)">
					<img :src="'/uploads/imagesProduct/' + recommend_product.thumbnail">
					<h4>{{ recommend_product.name }}</h4>
					<p><b>{{ recommend_product.price | FomatPrice }}</b></p>
				</div>
			</div>
	</div>
        <!-----footer---->
        <div class="footer">
            <div class="container">
                <div class="row">
                    <div class="footer-col-1">
                        <h3>Web K3MN</h3>
                    </div>
                    <div class="footer-col-2">
                        <h3>Sản phẩm của:</h3>
                        <ul>
                            <li>Nguyễn Quốc Khánh</li>
                            <li>Nguyễn Gia Khiêm</li>
                            <li>Nguyễn Huy Mạnh</li>
                            <li>Trần Minh Khương</li>
                            <li>Nguyễn Văn Nam</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
          'id',
        ],

        data() {
          return {
            product: [],
            user: [],
            comments: [],
            cmt_current_page: 1,
            cmt_last_page: 1,
            recommend_products: [],
            new_comment: {
                content: '',
            },
            images: [],
            set_user: false,
            show_acc: false,
            keyword: '',
            Avt_src: "",
            changeAvt: false,
          }
        },

        mounted() {
            axios.get('/api/profile')
                .then(response => {
                    this.user = response.data;
                    // console.log(this.user);
                })
                .catch(function(){
                    console.log('Loi tai user');
                });
            axios.get('/api/product/' + this.id)
                .then(response => {
                    this.product = response.data;
                    if(response.data[0].image !== "") {
                        this.images = JSON.parse(response.data[0].image);
                    }
                    // console.log('Thong tin san pham');
                    // console.log(this.product);
                })
                .catch(function(err){
                  console.log(err);
                });
            axios.get(`/api/product/${this.id}/comments`)
                .then(response => {
                    this.comments = response.data.data;
                    this.cmt_last_page = response.data.last_page;
                    // console.log('Danh sach comments');
                    // console.log(this.comments);
                })
                .catch(function(err){
                  console.log(err);
                });
            axios.get('/api/product/' + this.id + '/recommend')
                .then(response => {
                    this.recommend_products = response.data;
                    // console.log('Mang danh sách san pham duoc goi y');
                    // console.log(this.recommend_products);
                })
                .catch(function(){
                    console.log('Loi tai san pham duoc goi y');
                });
        },

        methods: {
            buy(event, product_id) {
                event.preventDefault();
                if (!this.set_user) {
                    window.location.href = '/login';
                    return;
                } else {
                    this.$router.push('/order/product/' + product_id);
                    return;
                };
            },
            gotoAvatar(image){
                this.Avt_src = '../uploads/imagesProduct/' + image;
                this.changeAvt = true;
            },

            comment(event, comment) {
                event.preventDefault();
                if (comment.content == '') {
                    return;
                }
                if (this.user.length == 0) {
                    window.location.href = '/login';
                    return;
                }
                comment.user_id = this.user[0].id;
                comment.product_id = this.product[0].id;
                axios.post('/api/comment', comment)
                .then(response => {
                    // console.log(response.data);
                })
                .catch(function(){
                  console.log('Loi binh luan');
                });
                this.comments.unshift({user_id: this.user[0].id, user_name: this.user[0].name, content: comment.content});
                comment.content = '';
                // console.log(this.comments);
            },

            search(event) {
                event.preventDefault();
                if (this.keyword == '') {
                    return;
                }
                this.$router.push('/products/search/' + this.keyword);
                this.keyword = '';
            },

            logout(event) {
                event.preventDefault();
                const user = {
                    id: this.user[0].id
                };
                axios.post('/api/logout', user)
                    .then(response => {
                        console.log(response);
						window.location.href = '/login';
                    })
                    .catch(function(){
						console.log('Loi dang xuat');
					});
			},

            moreComments() {
                this.cmt_current_page++;
                axios.get(`/api/product/${this.id}/comments?page=${this.cmt_current_page}`)
                .then(response => {
                    var more_comments = response.data.data;
                    more_comments.forEach(comment => {
                        this.comments.push(comment);
                    });
                    // console.log('Xem them danh sach comments');
                    // console.log(this.comments);
                })
                .catch(function(err){
                  console.log(err);
                });
            },
            goToProduct(id) {
                this.$router.push('/product/' + id);
                location.reload();
            }
        },

        watch: {
            user() {
                if(this.user.length > 0) {
                    this.set_user = true;
                }
                else {
                    this.set_user = false;
                }
            }
        },
        filters: {
            FomatPrice: function(value){
                return value.toLocaleString('it-IT', {style : 'currency', currency : 'VND'});
            },
        }
    }
</script>
<style src="../../css/css_product_details.css" scoped>