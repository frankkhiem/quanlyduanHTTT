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
          

          <!---lọc theo thể loại--->
        <div class="small-container">
          <div class="row row-2">
            <select @change="dieuhuongurl($event)">
              <option value="0">Tất cả sản phẩm</option>
              <option v-for="(category, index) in categories" :key="index" :value="category.id">{{ category.name }}</option>
            </select>
            
          </div>
          <router-view></router-view>
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
                user: [],
                categories: [],
                keyword: '',
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
          axios.get('/api/category')
              .then(response => {
                  this.categories= response.data;
                  // console.log(this.categories);
              })
              .catch(function(){
                console.log('Loi tai danh muc san pham');
              });
        },

        methods: {
          dieuhuongurl(event) {
            event.preventDefault();
            var category_id = event.target.value;
            if (category_id == 0) {
              this.$router.push('/products');
            }
            else {
              this.$router.push('/products/category/' + category_id);
            }
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
        }
    }
</script>

<style src="../../css/css_products.css" scoped>