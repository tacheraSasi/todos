from django.db import models
from django.contrib.auth.models import User

class Task(models.Model):
    # id = models.UUIDField(primary_key=True,auto_created=True)
    title = models.CharField(max_length=200)
    description = models.TextField()
    progress = models.IntegerField(default=0)  # value from 0 to 100 for the progress bar
    user = models.ForeignKey(User, on_delete=models.CASCADE)

    def __str__(self):
        return self.title
