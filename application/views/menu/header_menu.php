      <header>
        <div>
          <a href="#" class="logo"><img src="/wwf/img/logo.png" alt=""></a>
          <ul class="headerButtons">
		  	<li class="userint"><?php echo $this->session->userdata('uname'); ?>님 어서오세요</li>
		  	<li class="userint"><a class="dropdown-toggle" data-toggle="dropdown" href="#">자주쓰는 메뉴</a> <span class="arrow"></span>
				<ul class="dropdown-menu pull-left">
                  <li><a href="#">자주쓰는메뉴</a></li>
                  <li><a href="#">자주쓰는메뉴2</a></li>
                </ul>
			
			</li>
		  	<li class="userint"><a href="/auth/logout">로그아웃</a></li>
          </ul>

	  
        </div>

      </header>
