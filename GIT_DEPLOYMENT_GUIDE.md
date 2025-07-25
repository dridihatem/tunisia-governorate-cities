# Git Deployment Guide

This guide will help you deploy the Tunisia Governorate Cities WooCommerce plugin to Git and GitHub.

## Prerequisites

Before deploying, ensure you have:

1. **Git installed** on your system
2. **GitHub account** created
3. **GitHub repository** created (empty or with README)
4. **Git configured** with your credentials

## Quick Deployment

### Option 1: Automated Deployment (Recommended)

Use the provided deployment script for a complete automated process:

```bash
# Make script executable (if not already)
chmod +x deploy.sh

# Run full deployment
./deploy.sh deploy
```

This will:
- Initialize Git repository
- Setup GitHub remote
- Build assets
- Create initial commit
- Push to GitHub
- Create release tag
- Package the plugin

### Option 2: Manual Deployment

If you prefer manual control, follow these steps:

#### Step 1: Initialize Git Repository

```bash
# Initialize Git repository
git init

# Add all files
git add .

# Create initial commit
git commit -m "Initial commit: Tunisia Governorate Cities WooCommerce Plugin v1.0.0"
```

#### Step 2: Setup GitHub Remote

```bash
# Add GitHub remote (replace with your repository URL)
git remote add origin https://github.com/yourusername/tunisia-governorate-cities.git

# Verify remote
git remote -v
```

#### Step 3: Push to GitHub

```bash
# Push to main branch
git push -u origin main

# Create and push release tag
git tag -a v1.0.0 -m "Release version 1.0.0"
git push origin v1.0.0
```

## GitHub Repository Setup

### 1. Create Repository

1. Go to [GitHub.com](https://github.com)
2. Click "New repository"
3. Repository name: `tunisia-governorate-cities`
4. Description: `WordPress plugin for WooCommerce checkout with Tunisian governorates and cities`
5. Make it **Public** (recommended for open source)
6. **Don't** initialize with README (we have our own)
7. Click "Create repository"

### 2. Repository Settings

After creating the repository, configure these settings:

#### Repository Information
- **Description**: WordPress plugin for WooCommerce checkout with Tunisian governorates and cities
- **Website**: Your website URL
- **Topics**: `wordpress`, `plugin`, `woocommerce`, `tunisia`, `governorate`, `cities`, `checkout`

#### Features
- ✅ **Issues**: Enable for bug reports and feature requests
- ✅ **Discussions**: Enable for community discussions
- ✅ **Wiki**: Optional, for detailed documentation
- ✅ **Releases**: Enable for version releases

## Deployment Script Options

The `deploy.sh` script provides several options:

```bash
# Initialize Git repository and setup remote
./deploy.sh init

# Build assets (CSS/JS minification)
./deploy.sh build

# Create initial commit
./deploy.sh commit

# Push code to GitHub
./deploy.sh push

# Create release tag and package
./deploy.sh release

# Full deployment (recommended)
./deploy.sh deploy

# Show help
./deploy.sh help
```

## Post-Deployment Steps

### 1. Verify Repository

After deployment, verify your repository:

1. **Check files**: Ensure all plugin files are uploaded
2. **Check commits**: Verify commit history
3. **Check releases**: Confirm release tag is created
4. **Test download**: Download the plugin package

### 2. Update Repository Information

Update your repository with:

- **README.md**: Already included in the plugin
- **Description**: Detailed plugin description
- **Topics**: Relevant tags for discoverability
- **License**: GPL v2 or later (already included)

### 3. Create GitHub Release

1. Go to your repository on GitHub
2. Click "Releases" in the right sidebar
3. Click "Create a new release"
4. **Tag**: `v1.0.0`
5. **Title**: `Tunisia Governorate Cities for WooCommerce v1.0.0`
6. **Description**: Copy from CHANGELOG.md
7. **Upload**: Attach the plugin zip file
8. Click "Publish release"

### 4. Enable GitHub Pages (Optional)

For documentation website:

1. Go to repository Settings
2. Scroll to "Pages" section
3. Source: "Deploy from a branch"
4. Branch: `main`
5. Folder: `/ (root)`
6. Click "Save"

## Continuous Integration (Optional)

### GitHub Actions

Create `.github/workflows/ci.yml` for automated testing:

```yaml
name: CI

on:
  push:
    branches: [ main ]
  pull_request:
    branches: [ main ]

jobs:
  test:
    runs-on: ubuntu-latest
    
    steps:
    - uses: actions/checkout@v2
    
    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '7.4'
    
    - name: Install dependencies
      run: composer install --prefer-dist --no-progress
    
    - name: Run tests
      run: composer test
    
    - name: Check code style
      run: composer phpcs
```

### Automated Releases

Create `.github/workflows/release.yml` for automated releases:

```yaml
name: Release

on:
  push:
    tags:
      - 'v*'

jobs:
  release:
    runs-on: ubuntu-latest
    
    steps:
    - uses: actions/checkout@v2
    
    - name: Create Release
      uses: actions/create-release@v1
      env:
        GITHUB_TOKEN: ${{ secrets.GITHUB_TOKEN }}
      with:
        tag_name: ${{ github.ref }}
        release_name: Release ${{ github.ref }}
        body: |
          Changes in this Release:
          ${{ github.event.head_commit.message }}
        draft: false
        prerelease: false
```

## Troubleshooting

### Common Issues

#### 1. Authentication Issues
```bash
# Configure Git credentials
git config --global user.name "Your Name"
git config --global user.email "your.email@example.com"

# Use personal access token for HTTPS
git remote set-url origin https://YOUR_TOKEN@github.com/yourusername/tunisia-governorate-cities.git
```

#### 2. Permission Issues
```bash
# Make deployment script executable
chmod +x deploy.sh

# Check file permissions
ls -la deploy.sh
```

#### 3. Branch Issues
```bash
# Rename master to main (if needed)
git branch -M main

# Set upstream branch
git push -u origin main
```

#### 4. Tag Issues
```bash
# Delete local tag
git tag -d v1.0.0

# Delete remote tag
git push origin --delete v1.0.0

# Recreate tag
git tag -a v1.0.0 -m "Release version 1.0.0"
git push origin v1.0.0
```

### Getting Help

If you encounter issues:

1. **Check Git status**: `git status`
2. **Check remote configuration**: `git remote -v`
3. **Check branch**: `git branch -a`
4. **Check logs**: `git log --oneline`
5. **Create issue**: Use GitHub Issues for repository-specific problems

## Next Steps

After successful deployment:

1. **Share the repository**: Share the GitHub URL with others
2. **Create issues**: Add known issues or planned features
3. **Write documentation**: Add wiki pages if needed
4. **Monitor feedback**: Respond to issues and discussions
5. **Plan releases**: Schedule future version releases

## Repository Structure

Your deployed repository should look like this:

```
tunisia-governorate-cities/
├── .gitignore                 # Git ignore rules
├── .github/                   # GitHub configuration (optional)
├── assets/                    # Frontend assets
│   ├── css/                   # Stylesheets
│   └── js/                    # JavaScript files
├── languages/                 # Translation files
├── tunisia-governorate-cities.php  # Main plugin file
├── uninstall.php              # Cleanup script
├── README.md                  # Plugin documentation
├── CONTRIBUTING.md            # Contributing guidelines
├── CHANGELOG.md               # Version history
├── LICENSE                    # GPL v2 license
├── composer.json              # PHP dependencies
├── package.json               # Node.js dependencies
├── deploy.sh                  # Deployment script
└── GIT_DEPLOYMENT_GUIDE.md    # This file
```

## Support

For deployment issues:

- **GitHub Issues**: Create an issue in your repository
- **GitHub Discussions**: Start a discussion for questions
- **Documentation**: Check this guide and README.md
- **Community**: WordPress and WooCommerce communities

---

**Happy Deploying! 🚀**

Your Tunisia Governorate Cities WooCommerce plugin is now ready for the world! 