# Deployments

All deployments are done through the deploy.tappyn.com server


## Creating a new deployer

To add a new user, they need to be granted ssh access to the deploy server. The server user
is then given deploy rights to whichever servers they need to deploy on.

For example, we want to onboard new user John, with username `john_smith`

### Requirements

- Administrator access to necessary servers
- John Smith's public SSH key

### Add new deployer to deployment server

First, John needs access to deploy.tappyn.com.

```shell
ssh <admin_username>@deploy.tappyn.com
sudo adduser john_smith
Adding user `test_user' ...
Adding new group `test_user' (1003) ...
Adding new user `test_user' (1002) with group `test_user' ...
Creating home directory `/home/test_user' ...
Copying files from `/etc/skel' ...
Enter new UNIX password:

# A few other questions you fill out about the user
...

# Add John to the www-data group
sudo usermod -a -G www-data john_smith

# Now we add the SSH key he gave us
sudo mkdir /home/john_smith/.ssh
sudo sh -c `echo "<insert_the_public_key_here>" >> /home/john_smith/.ssh/authorized_keys`

# Fix permissions, since we had to execute as root
sudo chown -R john_smith: /home/john_smith

# Default the work directory to the deploy workspace
```
If John can now run `ssh john_smith@deploy.tappyn.com` without errors, we can proceed.

### Allow john_smith@deploy.tappyn access to remote servers

We need to add the server users deploy key to any remote servers that John needs access to.


To give deploy access to tappyn.com:

```shell
#**Run as administrator, on deploy.tappyn.com**
# Act as john_smith
su john_smith
cd
ssh-keygen -t rsa
# Just hit enter a few times, which saves key to default directory without a passphrase
# Finally, retrieve the generated key
cat ~/.ssh/id_rsa.pub

```
This key needs to be added to the deploy user on each server

```shell
ssh <admin_user>@<remote_server>

cd /home/deploy/.ssh
sudo sh -c `echo "<insert_the_public_key_here>" >> /home/john_smith/.ssh/authorized_keys`

```

At this point, John should be able to ssh into the remote server
```shell
ssh john_smith@deploy.tappyn.com
```

And from there, ssh into a remote server as deploy user
```shell
ssh deploy@<remote_server>
```

### Final steps

Unless specified, these are ran as john_smith@deploy.tappyn.com
#### Add Johns remote user to Github account
On deploy.tappyn.com, john_smith needs access to Git.
```shell
cat ~/.ssh/id_rsa.pub
```
John needs to copy that file into his Github Account. He should now be able to `git pull` in either application directory.
If it says it cant communicate with the SSH agent:
```shell
eval "$(ssh-agent -s)"
ssh-add ~/.ssh/id_rsa
# Test Github connection
ssh git@github.com
# It should say, "we connected to Git, but it doesnt allow shell access"
```

#### Set default workspace (*optional*)
```shell
echo 'cd /var/www/deployer >> ~/.bashrc
```
