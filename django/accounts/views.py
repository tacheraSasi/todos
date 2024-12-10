from django.shortcuts import render, redirect
from django.http import HttpResponse
from django.contrib.auth.decorators import login_required
from django.contrib import messages
from django.contrib.auth.models import User
from django.contrib.auth import authenticate, login ,logout
from .models import Task

def home(request):
    messages.success(request,"You have been redirect to the login page")
    return redirect('login')

def loginPage(request):
    return render(request,"accounts/login.html")


def loginPost(request):
    if request.method == "POST":
        username = request.POST['username']
        password = request.POST['password']
        user = authenticate(request,username=username,password=password)
        print(user)
        
        if user is not None:
            login(request,user)
            return redirect('dashboard')
        else:
            messages.error(request,"Invalid credentails")
            return redirect('login')
    else:
        return HttpResponse("Invalid method")

def register(request):
    return render(request,"accounts/register.html")

def registerPost(request):
    if request.method == "POST":
        username = request.POST['username']
        email = request.POST['email']
        password = request.POST['password']
        confirm_password = request.POST['confirm_password']
        
        if username and email and password and confirm_password:
            #register logic here
            if password == confirm_password :
                try:
                    user = User.objects.create_user(
                        username=username,
                        email=email,
                        password=password
                    ) 
                    user.save()
                    messages.success(request,"You have successfully registered to django tasks")
                    return redirect("login")
                except:
                    #NOTE:This error will possibly occur if the user already exists
                    #TODO: I will add a separate logic for checking if the user already exist 
                    messages.error(request,"Something went wrong failed to register")
                    redirect('register')
            else:
                messages.error(request,"Passwords do not match")
                return redirect('register')
                    
        else:
            messages.error(request,"Missing field. Please make sure you fill all the inputs")
            return  redirect('register')   
    else:
        return HttpResponse("Invalid method")

@login_required
def dashboard(request):
    tasks = Task.objects.all()#fix this to only fetch the task for the logged in users
    context = {"tasks":tasks}
    return render(request,"accounts/dashboard.html",context)

def welcome(request):
    return render(request,"accounts/welcome.html")

def logout_view(request):
    logout(request)
    redirect('login')
    print("User has logged out successfully")
    
    
#Tasks views
def taskCreate(request):
    
    if request.method == "POST":
        title = request.POST['name']
        description = request.POST['desc']
        progress = request.POST['progress']
        fromURL = request.POST['from_url']
        # user = request.POST['user']
        
        if title is not None:
            task = Task.objects.create(
                title=title,
                description=description,
                progress=progress,
                # user=user
            )
            task.save()
            messages.success(request,"Task created succesfully")
            return redirect(URL=fromURL)
        else:
            messages.error(request,"Task title cannot be empty")
            return redirect(URL=fromURL)
        
    return HttpResponse("Invalid Method, Access Denied")