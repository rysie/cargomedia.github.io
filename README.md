www.cargomedia.ch
=================

Initial installation
--------------------

Install necessary tools:
```
gem install jekyll
gem install jekyll-less therubyracer
gem install s3_website
```

On AWS create a bucket `www.cargomedia.ch` and IAM user with permissions as follows:
```
{
  "Statement": [
    {
      "Effect": "Allow",
      "Action": "s3:*",
      "Resource": "arn:aws:s3:::www.cargomedia.ch"
    },
    {
      "Effect": "Allow",
      "Action": "s3:*",
      "Resource": "arn:aws:s3:::www.cargomedia.ch/"
    },
    {
      "Effect": "Allow",
      "Action": "s3:*",
      "Resource": "arn:aws:s3:::www.cargomedia.ch/*"
    }
  ]
}
```

Configure S3 bucket:
```
s3_website cfg apply
```

Build and upload website
------------------------

```
jekyll build
s3_website push --headless
```

