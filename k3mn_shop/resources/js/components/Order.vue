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
        <div class="small-container">
        <div v-if="product[0] != null && user[0] != null">
            <h1>Tiến hành đặt sản phẩm:</h1>
            
            <div>
                <div class="row">
                    <div class="col70">
                         <div class="small-row">
                    <label class="infor" for="">Tên khách hàng:</label>
                    <input class="infor-input" type="text" v-model="user[0].name" disabled>
                </div>
                <div class="small-row">
                    <label class="infor" for="">Số điện thoại đặt hàng:</label>
                    <input class="infor-input" type="text" v-model="order.phone_order">
                </div>
                <div class="small-row">
                    <label class="infor" for="">Địa chỉ đặt hàng:</label>
                    <input class="infor-input" type="text" v-model="order.address_order">
                </div>
                <div class="small-row">
                    <label class="infor1" for="">Đơn giá:</label>
                    <p>{{ product[0].price | FomatPrice}}</p>
                </div>
                <div class="small-row">
                    <label class="infor2" for="" margin-top="2.5px">Số lượng đặt hàng:</label>
                    <div>
                        <button @click="sub()" v-if="number > 1">-</button>
                        <button v-else @click="sub()" class="nosub">-</button>
                        <span>{{ number }}</span>
                        <button @click="add()">+</button>
                    </div>
                </div>
                    </div>
                    <div class="col20">
                        <h3>{{ product[0].name }}</h3>
                        <img :src="'/uploads/imagesProduct/' + product[0].thumbnail">
                    </div>
                </div>
               
                <hr>
            <div class="total-price">
			    <table>
				<tr>
					<td>Tổng tiền</td>
					<td>{{ this.number*this.product[0].price | FomatPrice}}</td>
				</tr>
				<tr>
					<td>Giảm giá</td>
					<td>{{ discout | FomatPrice}}</td>
				</tr>
				<tr>
					<td>Thanh toán</td>
					<td>{{ this.number*this.product[0].price - this.discout | FomatPrice}}</td>
				</tr>
			</table>
		</div>
                <button class="btn" @click="completeOrder()" v-show="completed == false">Hoàn tất</button>
                <button class="btn-cancel" @click="cancelOrder()" v-show="completed == false">Hủy</button>
                <div v-show="completed == true">
                    <h3>Bạn đã đặt hàng thành công</h3>
                    <router-link :to="{name: 'AllProducts'}" class="btn">Tiếp tục mua hàng</router-link>
                    <router-link :to="{name: 'ListOrders'}" class="btn">Lịch sử đặt hàng</router-link>
                </div>
            </div>
        </div>
    </div>
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
                order: {},
                number: 1,
                completed: false,
                discout: 0,
                set_user: false,
                show_acc: false,
            }
        },

        mounted() {
            axios.get('/api/product/' + this.id)
                .then(response => {
                    this.product = response.data;
                    // console.log(this.product);
                })
                .catch(function(){
                  console.log('Loi tai thong tin san pham');
                });
            axios.get('/api/profile')
                .then(response => {
                    this.user = response.data;
                    // console.log(this.user);
                })
                .catch(function(){
                    console.log('Loi tai user');
                });
        },

        methods: {
            sub() {
                if (this.number > 1) {
                    this.number--;
                }
            },
            add() {
                this.number++;
            },
            completeOrder() {
                this.order.user_id = this.user[0].id;
                this.order.product_id = this.product[0].id;
                this.order.quantity = this.number;
                this.order.sale_price = this.product[0].price;
                this.order.status_order_id = 1;
                this.order.thumbnail =  this.product[0].thumbnail
                axios.post('/api/order', this.order)
                .then(response => {
                    // console.log(response.data);
                    this.completed = true;
                })
                .catch(function(){
                  console.log('Loi dat hang');
                });
            },
            cancelOrder() {
                this.$router.push('/products');
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
<style src="../../css/css_order.css" scoped>

