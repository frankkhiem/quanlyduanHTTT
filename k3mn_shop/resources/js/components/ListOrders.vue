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
        <div v-if="user[0] != null">
            <h1>Lịch sử mua hàng của: {{ user[0].name }}</h1>
            <h2>Danh sách đơn hàng</h2>
        </div>
        <div class="small-container cart-page">
		<table>
			<tr>
				<th>Sản phẩm</th>
				<th>Số lượng</th>
				<th>Tình trạng đơn hàng</th>
				<th>Cập nhật</th>
			</tr>
            <tr v-for="(order, index) in orders" :key="index">
                <td>
					<div class="cart-info">
						<img :src="'/uploads/imagesProduct/' + order.thumbnail">
						<div>
							<p>{{ order.product_name }}</p>
							<small>Giá: {{ order.sale_price | FomatPrice }}</small>
							<br>
							<a href="" @click="orderDetail($event, order.id)">Xem chi tiết</a>
						</div>
					</div>
				</td>
				<td><span>{{ order.quantity }}</span></td>
				<td v-if="order.status_name == 'Chờ xác nhận'">{{ order.status_name }}</td>
                <td v-else-if="order.status_name == 'Đang vận chuyển'" class="ship">{{ order.status_name }}</td>
                <td v-else class="complete">{{ order.status_name }}</td>
				<td>{{ order.updated_at }}</td>
            </tr>
        </table>
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
        data() {
            return {
                orders: [],
                totalAmount: [],
                totalOrders: -1,
                user: [],
                set_user: false,
                show_acc: false,
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
            axios.get('/api/listorders')
                .then(response => {
                    this.orders = response.data.listOrders;
                    this.totalAmount = response.data.totalAmount;
                    this.totalOrders = response.data.totalOrders;
                    // console.log(this.orders);
                    // console.log(this.totalAmount);
                    // console.log(this.totalOrders);
                })
                .catch(function(){
                  console.log('Loi tai danh sach don dat hang');
                });
        },

        methods: {
            orderDetail(event, id) {
                event.preventDefault();
                this.$router.push('/order/detail/' + id);
                return;
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
<style src="../../css/css_listOrders.css" scoped>
