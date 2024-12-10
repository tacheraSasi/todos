from django.urls import path

from . import views

urlpatterns = [
    path('login/',views.loginPage,name="login"),
    path('login-post/',views.loginPost,name="login-post"),
    path('register/',views.register,name="register"),
    path('register-post/',views.registerPost,name="register-post"),
    path('logout/',views.logout_view,name="logout"),
    path('dashboard/',views.dashboard,name="dashboard"),
    path('task/create/',views.taskCreate,name="task-create")
]